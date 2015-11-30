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
	 * @param  string $type View type ('page' or 'exercise')
	 * @return array  $menu Navbar menu
	 */
	public function printNavBarMenu($id, $type) {

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
		$data['refresh_icon'] 	= $this->printRefreshIcon($id, $type);
		$data['session_icon'] 	= $this->printSessionIcon();

		return $data;
	}

	/**
	 * Print refresh icon
	 *
	 * @param  int 	  $id   Subtopic/Exercise ID
	 * @param  string $type View type ('page' or 'exercise')
	 * @return string $html Html-code
	 */
	public function printRefreshIcon($id, $type) {

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
	 * Print session icon
	 *
	 * @return void
	 */
	public function printSessionIcon() {

		$href = base_url().'view/session/';
		
		$html = '';

		if ($this->session->userdata('Logged_in')) {

			$html .= '<li>'."\n";
			$html .= "\t".'<a href="'.$href.'">'."\n";
			$html .= "\t\t".'<span class="glyphicon glyphicon-menu-hamburger"></span> Tevékenységek'."\n";
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
	public function printPageTitle($id, $type) {

		if ($id) {

			if ($type=='page') {

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

				$this->db->select('subtopics.name')
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
			'img' 		=> $img,
			'title' 	=> $title,
			'subtitle' 	=> $subtitle,
			'type' 		=> $type,
			'id'		=> $id
		);
	}

	/**
	 * Get exercise links
	 *
	 * @param  int   $id    Excercise ID
	 * @return array $links Links
	 */
	public function getExerciseLinks($id=NULL) {

		$links = [];

		if ($id) {

			$exercises1 = $this->db->get_where('links', array('exerciseID' => $id));
			foreach ($exercises1->result() as $exercise1) {
				$exercises2 = $this->db->get_where('exercises', array('label' => $exercise1->label));
				$exercise2 = $exercises2->result()[0];
				$id = $exercise2->id;

				if (isset($this->session->userdata('results')[$id])) {
					$level_user = $this->session->userdata('results')[$id];
				} else {
					$level_user = 0;
				}

				$link['id'] 		= $id;
				$link['name'] 		= $exercise2->name;
				$link['level_max'] 	= $exercise2->level;
				$link['level_user']	= $level_user;

				$links[] = $link;
			}
		}

		return $links;
	}

	/**
	 * Get exercises of subtopic
	 *
	 * @param  int   $id   Subtopic ID
	 * @return array $data Exercises
	 */
	public function getExercises($id) {

		$query = $this->db->get_where('exercises', array('subtopicID' => $id));
		foreach ($query->result() as $exercise) {

			$id = $exercise->id;
			if (isset($this->session->userdata('results')[$id])) {
				$level_user = $this->session->userdata('results')[$id];
			} else {
				$level_user = 0;
			}

			$row['level_user'] 	= $level_user;
			$row['id'] 			= $id;
			$row['name'] 		= $exercise->name;
			$row['level_max'] 	= $exercise->level;

			$data['exercise_list'][] = $row;
		}

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * @param  int   $id    Exercise ID
	 * @param  int   $level Exercise level
	 * @return array $data  Exercise data
	 */
	public function getExerciseData($id, $level) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 
		$label 		= $exercise->label;

		$this->load->model('Exercises');

		$data 				= $this->Exercises->$label($level);
		$data['level'] 		= $level;
		$data['youtube'] 	= $exercise->youtube;
		$data['id'] 		= $id;
		$data['links'] 		= $this->getExerciseLinks($id);
		$data['next'] 		= $this->getNextExercise($id, $level);

		return $data;
	}

	/**
	 * Get next exercise
	 *
	 * @param  int   $id    Exercise ID
	 * @param  int   $level Exercise level
	 * @return array $data  Next exercise
	 */
	public function getNextExercise($id, $level) {

		$data['label'] = 'Tovább';

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise1 = $query->result()[0];
		$max_level = $exercise1->level;

		if ($level < $max_level) {

			$data['href'] = 'view/exercise/'.strval($id).'/'.strval($level+1);

 		} else {

 			$method = $this->session->userdata('method');
 			$goal = $this->session->userdata('goal');

 			if ($method == 'subtopic') {
				$query = $this->db->get_where('exercises', array('subtopicID' => $goal));
				$exercises = $query->result();
				shuffle($exercises);
				foreach ($exercises as $exercise) {
					$id_next = $exercise->id;
					$level_max = $exercise->level;
					if (isset($this->session->userdata('results')[$id_next])) {
						$level_user = $this->session->userdata('results')[$id_next];
						if ($level_user < $level_max) {
							$data['href'] = 'view/exercise/'.strval($id_next);
							return $data;
						}
					} else {
						$data['href'] = 'view/exercise/'.strval($id_next);
						return $data;
					}
				}

 				$data['href'] = 'view/page/'.$goal;
				$data['label'] = 'Kész! :)';
				return $data;

 			} elseif ($method == 'exercise') {

 				if ($goal == $id || $level == $max_level) {
	 				$data['href'] = 'view/page';
					$data['label'] = 'Kész! :)';
					return $data;
 				}
 			}

 			// $exercises2 = $this->db->get_where('links', array('label' => $exercise1->label));
 			// $num_res2 = count($exercises2->result());

 			// if ($num_res2 > 0) {

 			// 	$id_next = $exercises2->result()[rand(1,$num_res2)-1]->exerciseID;
 			// 	$data['href'] = 'view/exercise/'.strval($id_next);

 			// } else {

 			// 	$data['href'] = 'view/page/';
				// $data['label'] = 'Kész! :)';
 			// }
 		}

 		return $data;
	}

	/**
	 * Get exercise from subtopicID
	 *
	 * @param  int   $subtopicID Subtopic ID
	 * @return array $data       Exercise data
	 */
	public function getExerciseFromSubtopicID($subtopicID) {



		$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
		$exercises = $query->result_array();
		shuffle($exercises);

		foreach ($exercises as $exercise) {
			$id = $exercise['id'];
			if (isset($this->session->userdata('results')[$id])) {
				$level_user = $this->session->userdata('results')[$id];
				$level_max = $exercise['level'];

				if ($level_user < $level_max) {
					$exerciseID = $id;
					$level = $level_user+1;
					break;
				}
				
			} else {

				$exerciseID = $id;
				$level = 1;
			}
		}

		// all exercise done
		if (!isset($exerciseID) || !isset($exerciseID)) {

			$status = 'FULL';
			$exerciseID = NULL;
			$level = NULL;

		} else {

			$status = 'OK';

		}

 		return array(
 			'href'	 => base_url().'view/exercise/'.$exerciseID.'/'.$level,
 			'status' => $status
		);
	}
}

?>