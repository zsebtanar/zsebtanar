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
	 * @return array $menu Navbar menu
	 */
	public function printNavBarMenu() {

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

		return $menu;
	}

	/**
	 * Print refresh icon
	 *
	 * @param  int 	  $id   Subtopic/Exercise ID
	 * @param  string $type View type ('page' or 'exercise')
	 * @return string $html Html-code
	 */
	public function printRefreshIcon($id=NULL, $type) {

		switch ($type) {
			case 'page':
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
	 * @param  string $type Page type ('subtopic' or 'exercise' or 'message')
	 * @return string $html Html-code
	 */
	public function printPageTitle($id=NULL, $type='subtopic') {

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

			} else {

				$exercises = $this->db->get_where('exercises', array('id' => $id));
				$exercise = $exercises->result()[0];

				$this->db->select('subtopics.name');
				$this->db->from('exercises');
				$this->db->join('subtopics', 'subtopics.id = exercises.subtopicID', 'inner');
				$subtopics = $this->db->get();
				$subtopic = $subtopics->result()[0];

				$title = $exercise->name;
				$subtitle = $subtopic->name;
			}

			$img = '';

		} else {

			if ($type=='message') {

				$title = 'Üzenet küldése';
				$subtitle = '';
				$img = '';

			} else {

				$title = 'zsebtanár';
				$subtitle = 'matek | másként';
				$img = '<a href="'.base_url().'view/page"><img class="img-responsive center-block img_main" '
					.'src="'.base_url().'assets/images/logo.png" alt="logo" width="150"></a>';
			}
		}

		return array(
			'img' => $img,
			'title' => $title,
			'subtitle' => $subtitle,
		);
	}

	/**
	 * Print exercise links
	 *
	 * @param  int   $id    Excercise ID
	 * @return array $links Links
	 */
	public function printExerciseLinks($id=NULL) {

		$links = [];

		if ($id) {

			$exercises1 = $this->db->get_where('links', array('exerciseID' => $id));
			foreach ($exercises1->result() as $exercise1) {
				$exercises2 = $this->db->get_where('exercises', array('label' => $exercise1->label));
				$exercise2 = $exercises2->result()[0];

				$link['id'] = $exercise2->id;
				$link['name'] = $exercise2->name;

				$links[] = $link;
			}
		}

		return $links;
	}
}

?>