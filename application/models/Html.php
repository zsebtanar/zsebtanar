<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		$this->load->helper('url');
	}

	/**
	 * Print navbar menu
	 *
	 * @param  int 	  $id   Exercise ID
	 * @param  string $type View type ('subtopic' or 'exercise')
	 * @return array  $menu Navbar menu
	 */
	public function NavBarMenu($id, $type) {

		$classes = $this->db->get('classes');

		foreach ($classes->result() as $class) {

			$topics = $this->db->get_where('topics', array('classID' => $class->id));

			if (count($topics) > 0) {

				foreach ($topics->result() as $topic) {

					$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));

					if (count($subtopics) > 0) {

						foreach ($subtopics->result() as $subtopic) {

							$menu[$class->name][$topic->name][$subtopic->id] = $subtopic->name;

						}
					}

				}
			}
		}

		$data['html'] 			= $menu;
		$data['refresh_icon'] 	= $this->RefreshIcon($id, $type);

		return $data;
	}

	/**
	 * Print refresh icon
	 *
	 * @param  int 	  $id   Subtopic/Exercise ID
	 * @param  string $type View type ('subtopic' or 'exercise')
	 * @return string $html Html-code
	 */
	public function RefreshIcon($id, $type) {

		switch ($type) {
			case 'subtopic':
				if ($id) {
					$subtopicID = $id;
				} else {
					$subtopicID = '';
				}
				break;

			case 'exercise':
				if ($id) {
					$exercises = $this->db->get_where('exercises', array('id' => $id));
					$exercise = $exercises->result()[0];
					$subtopicID = $exercise->subtopicID;
				} else {
					$subtopicID = '';
				}
				break;
		}


		$href = base_url().'update/database/'.$subtopicID;
		
		$html = '';

		if ($this->session->userdata('Logged_in')) {

			$html .= '<li>'."\n";
			$html .= "\t".'<a href="'.$href.'">'."\n";
			$html .= "\t\t".'<span class="glyphicon glyphicon-refresh"></span> Frissítés'."\n";
			$html .= "\t".'</a>'."\n";
			$html .= '</li>'."\n";

		}

		return $html;
	}

	/**
	 * Print page title
	 *
	 * @param  int    $id   Subtopic/Excercise ID
	 * @param  string $type Page type (subtopic/exercise)
	 * @return string $html Html-code
	 */
	public function Title($id, $type) {

		if ($id) {

			if ($type=='subtopic') {

				$subtopics = $this->db->get_where('subtopics', array('id' => $id));
				$subtopic = $subtopics->result()[0];

				$this->db->select('classes.name');
				$this->db->from('subtopics');
				$this->db->join('topics', 'topics.id = subtopics.topicID', 'inner');
				$this->db->join('classes', 'classes.id = topics.classID', 'inner');
				$classes = $this->db->get();
				$class = $classes->result()[0];

				$title = $subtopic->name;
				$subtitle = $class->name;
				$img = '';

			} else {

				$query = $this->db->get_where('exercises', array('id' => $id));
				$exercise = $query->result()[0];

				$this->db->select('subtopics.name, subtopics.id')
						->distinct()
						->from('subtopics')
						->join('exercises', 'subtopics.id = exercises.subtopicID', 'inner')
						->where('exercises.id', $id);
				$subtopics = $this->db->get();
				$subtopic = $subtopics->result()[0];

				$level = $exercise->level;

				if (isset($this->session->userdata('results')[$id])) {
					$user_level = $this->session->userdata('results')[$id];
				} else {
					$user_level = 0;
				}

				for ($i=1; $i <= $level; $i++) {

					$img[$i] = 0;

					if ($i <= $user_level) {
						$img[$i] = 1;
					}
				}

				$title = $exercise->name;
				$subtitle = $subtopic->name;

			}

			$href = base_url().'view/subtopic/'.$subtopic->id;

		} else {

			$title = 'zsebtanár';
			$subtitle = 'matek | másként';
			$img = '<a href="'.base_url().'view/subtopic"><img class="img-responsive center-block img_main" '
				.'src="'.base_url().'assets/images/logo.png" alt="logo" width="150"></a>';
			$href = NULL;
		}

		return array(
			'img' 		=> $img,
			'title' 	=> $title,
			'subtitle' 	=> $subtitle,
			'type' 		=> $type,
			'id'		=> $id,
			'href'		=> $href
		);
	}
}

?>