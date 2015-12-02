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
	 * @param  string $type View type ('subtopic' or 'exercise')
	 * @return string $html Html-code
	 */
	public function printRefreshIcon($id, $type) {

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
	 * @param  string $type Page type (subtopic/exercise)
	 * @return string $html Html-code
	 */
	public function printTitle($id, $type) {

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

	/**
	 * Get exercises of subtopic
	 *
	 * @param  int   $id   Subtopic ID
	 * @return array $data Exercises
	 */
	public function getExerciseList($id) {

		$query = $this->db->get_where('exercises', array('subtopicID' => $id));

		$exercises = $query->result();

		if (count($exercises) > 0) {
			foreach ($exercises as $exercise) {

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
		} else {
			$data = [];
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
		$data['prev']		= $this->getPreviousExercise($id);

		return $data;
	}

	/**
	 * Get next exercise
	 *
	 * @param  int   $id    Exercise ID
	 * @param  int   $level Exercise level
	 * @return array $data  Next exercise
	 */
	public function getNextExercise($id, $level=1) {

		$data['label'] = 'Tovább';

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise1 = $query->result()[0];
		$max_level = $exercise1->level;

		if ($level < $max_level) {

			$data['href'] = base_url().'view/exercise/'.strval($id);

 		} else {

 			if (NULL !== $this->session->userdata('method') &&
 				NULL !== $this->session->userdata('goal')) {

	 			$method = $this->session->userdata('method');
	 			$goal = $this->session->userdata('goal');

	 			if ($method == 'subtopic') {

	 				$data['label'] = 'Tovább';

					$query = $this->db->get_where('exercises', array('subtopicID' => $goal));
					$exercises = $query->result();
					
					foreach ($exercises as $exercise) {
						$id_next = $exercise->id;
						$level_max = $exercise->level;
						$results = $this->session->userdata('results');
						if (isset($results[$id_next])) {
							$level_user = $results[$id_next];
							if ($level_user < $level_max) {
								$data['href'] = base_url().'view/exercise/'.strval($id_next);
								break;
							}
						} else {
							$data['href'] = base_url().'view/exercise/'.strval($id_next);
							break;
						}
					}

					if (!isset($data['href'])) {

		 				$data['label'] = 'Kész! :)';
						$data['href'] = base_url().'view/subtopic/'.$goal;

					}
				
					return $data;

	 			} elseif ($method == 'exercise') {

	 				if ($goal == $id) {

	 					$data['label'] = 'Kész! :)';
						$data['href'] = base_url().'view/subtopic/';

	 				} else {

	 					$todo_list = $this->session->userdata('todo_list');

	 					foreach ($todo_list as $key => $value) {
	 						if ($value == $id) {
	 							unset($todo_list[$key]);
	 						}
	 					}

	 					$last = array_slice($todo_list, -1, 1);
	 					$data['href'] = base_url().'view/exercise/'.strval($last[0]);

	 					$this->session->set_userdata('todo_list', $todo_list);
		 			}

	 			}

 			} else {

 				$query = $this->db->get_where('links', array('label' => $exercise1->label));
 				$links = $query->result();

 				if (count($links) > 0) {
 					
					foreach ($links as $link) {

						$query = $this->db->get_where('exercises', array('id' => $link->exerciseID));
						$exercise = $query->result()[0];
						$id_next = $exercise->id;
						$level_max = $exercise->level;
						if (isset($this->session->userdata('results')[$id_next])) {
							$level_user = $this->session->userdata('results')[$id_next];
							if ($level_user < $level_max) {
								$data['href'] = base_url().'view/exercise/'.strval($id_next);
								break;
							}
						} else {
							$data['href'] = base_url().'view/exercise/'.strval($id_next);
							break;
						}
					}

 				} else {

 					$data['href'] = base_url().'view/subtopic';
					$data['label'] = 'Kész! :)';
 				}
 			}
 		}

 		return $data;
	}

	/**
	 * Get previous exercise
	 *
	 * @param  int   $id   Exercise ID
	 * @return array $href Link to previous exercise
	 */
	public function getPreviousExercise($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise1 = $query->result()[0];
		$query = $this->db->get_where('links', array('exerciseID' => $id));
		$links = $query->result();

		if (count($links) > 0) {
			
			shuffle($links);

			foreach ($links as $link) {

				$query = $this->db->get_where('exercises', array('label' => $link->label));
				$exercise2 = $query->result()[0];
				
				$id_prev = $exercise2->id;
				$level_max = $exercise2->level;
				$results = $this->session->userdata('results');

				if (!isset($results[$id_prev])) {

					$href = base_url().'view/exercise/'.strval($id_prev);
					break;

				} elseif ($results[$id_prev] < $level_max) {

					$href = base_url().'view/exercise/'.strval($id_prev);
					break;

				} else {

					$href = base_url().'view/exercise/'.strval($id_prev);
					// $href = NULL;
				}
			}

		} else {

			$href = NULL;
		}

 		return $href;
	}

	/**
	 * Get next avaliable exercise for subtopic
	 *
	 * Checks whether user has complated all exercises of subtopics.
	 * If not, returns link to next available exercise.
	 * If so, returns link to clear results session.
	 *
	 * @param  int   $subtopicID Subtopic ID
	 * @return array $data       Exercise data
	 */
	public function getNextExerciseSubtopic($subtopicID) {

		$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
		$exercises = $query->result_array();

		foreach ($exercises as $exercise) {

			$id = $exercise['id'];
			$results = $this->session->userdata('results');

			if (isset($results[$id])) {

				$level_user = $results[$id];
				$level_max = $exercise['level'];

				if ($level_user < $level_max) {
					$id_next = $id;
					break;
				}
				
			} else {

				$id_next = $id;
				break;
			}
		}

		// all exercise done
		if (!isset($id_next)) {

			$href = base_url().'application/clearresults/'.$subtopicID;
			$name = 'Újrakezd';
			$id_next = NULL;
			$level_next = NULL;

		} else {

			$href = base_url().'application/setgoal/subtopic/'.$subtopicID;
			$name = 'Gyakorlás';

		}

 		return array(
 			'href' 			=> $href,
 			'name' 			=> $name,
 			'id_next' 		=> $id_next
		);
	}
}

?>