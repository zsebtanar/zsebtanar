<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->load->helper('url');
		defined('RESOURCES_URL') OR define('RESOURCES_URL', base_url('resources/exercises'));
	}

	/**
	 * Get main page data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return array $data Main page data
	 */
	public function MainData($classID=NULL, $topicID=NULL) {

		$data['subtopics'] 	= $this->getSubtopics($classID, $topicID);
		$data['classID'] 	= $classID;
		$data['topicID'] 	= $topicID;
		$data['type'] 		= 'main';
		$data['titledata'] 	= NULL;
		$data['results'] 	= $this->Session->GetResults();

		return $data;
	}

	/**
	 * Get breadcrumb
	 *
	 * Collects links to current class/suptopic/exercise etc.
	 *
	 * @param string $type Type of id
	 * @param int    $id   Id
	 *
	 * @return array $data Breadcrumb data
	 */
	public function BreadCrumb($type, $id) {

		if ($type == 'subtopic') {

			$data['prev'] = $this->SubtopicLink($id-1);
			$data['next'] = $this->SubtopicLink($id+1);

		} elseif ($type == 'exercise') {

			$data['prev'] = $this->ExerciseLink($id-1);
			$data['next'] = $this->ExerciseLink($id+1);

		}
		
		$data['results'] = $this->Session->GetResults();

		return $data;
	}

	/**
	 * Get subtopic data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $subtopicID Subtopic ID
	 * @param int $exerciseID Exercise ID
	 *
	 * @return array $data Subtopic data
	 */
	public function SubtopicData($subtopicID=NULL, $exerciseID=NULL) {

		$data['type'] 		= 'subtopic';
		$data['exercises']	= $this->SubtopicExercises($subtopicID, $exerciseID);
		$data['results']	= $this->Session->GetResults('subtopic', $subtopicID);
		$data['breadcrumb'] = $this->BreadCrumb('subtopic', $subtopicID);
		$data['title']		= $this->SubtopicTitle($subtopicID);

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $id    Exercise ID
	 * @param int $level Exercise level
	 *
	 * @return array $data Exercise data
	 */
	public function ExerciseData($id, $level) {

		$data['type'] 		= 'exercise';
		$data['results'] 	= $this->Session->GetResults('exercise', $id);
		$data['exercise'] 	= $this->GetExerciseData($id, $level);
		$data['breadcrumb'] = $this->BreadCrumb('exercise', $id);

		$data['results']['id'] = $id;
		$data['results']['type'] = 'exercise';

		$data['progress'] 	= $this->Session->UserProgress($id);

		return $data;
	}

	/**
	 * Get subtopics
	 *
	 * Gets all subtopics for each topic, and all topics for each class
	 *
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return array $data Subtopics
	 */
	public function getSubtopics($classID=NULL, $topicID=NULL) {

		$this->db->order_by('id');
		$classes = $this->db->get('classes');

		foreach ($classes->result() as $class) {

			$this->db->order_by('id');
			$topics = $this->db->get_where('topics', array('classID' => $class->id));
			$topics_menu = [];
			$class_menu['show'] = FALSE;

			if (count($topics) > 0) {

				foreach ($topics->result() as $topic) {

					$this->db->order_by('id');
					$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));
					$subtopics_menu = [];
					$topic_menu['show'] = FALSE;

					if (count($subtopics) > 0) {

						foreach ($subtopics->result() as $subtopic) {

							$subtopic_menu['id'] 	= $subtopic->id;
							$subtopic_menu['name'] 	= $subtopic->name;
							$subtopic_menu['show']	= FALSE;

							$subtopic_status = $this->Database->SubtopicStatus($subtopic->id);

							if ($this->Session->CheckLogin() || $subtopic_status == 'OK') {
								$subtopic_menu['show'] 	= TRUE;
								$topic_menu['show'] 	= TRUE;
								$class_menu['show'] 	= TRUE;
							}

							$subtopics_menu[] = $subtopic_menu;
						}
					}

					$topic_menu['id'] = $topic->id;
					$topic_menu['name'] = $topic->name;
					$topic_menu['subtopics'] = $subtopics_menu;

					if ($this->Session->CheckLogin() || $topic->id == $topicID || (!$topicID && $topic->classID == $classID)) {
						$topic_menu['class'] = 'in';
					} else {
						// $topic_menu['class'] = '';
						$topic_menu['class'] = 'in';
					}
					

					$topics_menu[] = $topic_menu;
				}
			}

			$class_menu['id'] = $class->id;
			$class_menu['name'] = $class->name;
			$class_menu['topics'] = $topics_menu;

			if ($this->Session->CheckLogin() || $class->id == $classID) {
				$class_menu['class'] = 'in';
			} else {
				$class_menu['class'] = '';
			}

			$classes_menu[] = $class_menu;
		}

		$data['classes'] = $classes_menu;

		return $data;
	}

	/**
	 * Get subtopic title
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $title Title
	 */
	public function SubtopicTitle($id) {

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));

		if (count($subtopics->result()) > 0) {

			$subtopic = $subtopics->result()[0];
			$title = $subtopic->name;

		} else {

			$title = 'Kezdőlap';

		}

		return $title;
	}

	/**
	 * Get link for subtopic
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $link Link
	 */
	public function SubtopicLink($id) {

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));

		if (count($subtopics->result()) > 0 &&
			($this->Session->CheckLogin() || $this->Database->SubtopicStatus($id) == 'OK')) {

			$subtopic = $subtopics->result()[0];
			$link = base_url().'view/subtopic/'.$subtopic->id;
			$name = $subtopic->name;

		} else {

			$link = base_url().'view/main/';
			$name = 'Kezdőlap';

		}

		return array(
			'link' => $link,
			'name' => $name
			);
	}

	/**
	 * Get ID of next exercise
	 *
	 * Checks whether user has completed all rounds of exercise.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $id_next Next exercise ID
	 */
	public function NextID($id) {

		$level_max  = $this->Database->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		$id_next = ($level_user < $level_max ? $id : $id+1);

 		return $id_next;
	}

	/**
	 * Get exercises of subtopic
	 *
	 * @param int $subtopicID Subtopic ID
	 * @param int $exerciseID Exercise ID
	 *
	 * @return array $data Exercises
	 */
	public function SubtopicExercises($subtopicID=NULL, $exerciseID=NULL) {

		if ($this->Session->CheckLogin()) {
			$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
		} else {
			$query = $this->db->get_where('exercises', array(
				'subtopicID' => $subtopicID,
				'status' => 'OK'
				));
		}

		$exercises = $query->result();
		$data = NULL;

		if (count($exercises) > 0) {
			foreach ($exercises as $exercise) {

				if ($this->Session->CheckLogin() || $exercise->status == 'OK') {

					$id = $exercise->id;

					$row['status'] 		= $exercise->status;
					$row['id'] 			= $id;
					$row['name'] 		= $exercise->name;
					$row['complete'] 	= $this->Session->isComplete($id);
					$row['progress'] 	= $this->Session->UserProgress($id);
					$row['class'] 		= (!$exerciseID || $id == $exerciseID ? 'in' : '');

					$exercisedata = $this->GetExerciseData($id, NULL, $save=FALSE);
					$row['question']	= $exercisedata['question'];

					$data[] = $row;

				}
			}
		}

		return $data;
	}

	/**
	 * Get link for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $href Link
	 */
	public function ExerciseLink($id) {

		$exercises = $this->db->get_where('exercises', array('id' => $id));

		if (count($exercises->result()) == 1) {


			$exercise = $exercises->result()[0];

			if ($this->Session->CheckLogin() || $exercise->status == 'OK') {

				$title = $exercise->name;
				$link = base_url().'view/exercise/'.$exercise->id;
				$name = $exercise->name;

			} else {

				$link = base_url().'view/main/';
				$name = 'Kezdőlap';

			}

		} else {

			$link = base_url().'view/main/';
			$name = 'Kezdőlap';

		}

		return array(
			'link' 	=> $link,
			'name' 	=> $name
			);
	}

	/**
	 * Get exercise data
	 *
	 * @param int  $id    Exercise ID
	 * @param int  $level Exercise level
	 * @param bool $save  Should we save exercise data in session?
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($id, $level=NULL, $save=TRUE) {

		$this->load->helper('string');

		// Get exercise level
		if (!$level) {
			$level_user = $this->Session->getUserLevel($id);
			$level_max = $this->Database->getMaxLevel($id);

			$level = min($level_max, ++$level_user);
		}

		// Generate exercise
		// $this->load->helper('maths');
		// $this->load->helper('language');
		$label = $this->ExerciseLabel($id);

		$this->load->library($label);
		$function = strtolower($label);

		$data = $this->$function->Generate($level);

		if (!isset($data['type'])) {
			if (!isset($data['options'])) {
				$data['type'] = 'int';
			} elseif (is_array($data['options'])) {
				$data['type'] = 'quiz';
			}
		}

		if ($data['type'] == 'quiz') {
			$data = $this->getColumnWidth($data);
		}

		$data = $this->AddExplanation($id, $data);
		
		$hash = random_string('alnum', 16);

		if ($save) {
			$this->Session->SaveExerciseData($id, $level, $data, $hash);
		}

		$data['level'] 		= $level;
		$data['id'] 		= $id;
		$data['hash']		= $hash;
		$data['subtopicID'] = $this->Database->getSubtopicID($id);

		return $data;
	}

	/**
	 * Load exercise function
	 *
	 * Loads specific helper to access exercise function
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $exercise Exercise data
	 */
	public function ExerciseLabel($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 

		return $exercise->label;
	}

	/**
	 * Add explanation to exercise (if there is none)
	 *
	 * @param int   $id   Exercise id
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (with explanation)
	 */
	public function AddExplanation($id, $data) {

		if (isset($data['explanation'])) {
			if (is_array($data['explanation'])) {
				foreach ($data['explanation'] as $key1 => $segment) {
					if (is_array($segment)) {
						foreach ($segment as $key2 => $subsegment) {
							if ($key2 == 0) {
								if (is_array($subsegment)) {
									print_r($subsegment);
								}
								$explanation = $subsegment.'<button class="pull-right btn btn-default" data-toggle="collapse" data-target="#hint_details">Részletek</button><br/>';
								$explanation .= '<div id="hint_details" class="collapse well well-sm small">';
							} else {
								if (is_array($subsegment)) {
									foreach ($subsegment as $subsubsegment) {
										if (is_array($subsubsegment)) {
											print_r($subsubsegment);
											break;
										}
										$explanation .= '<p>'.strval($subsubsegment).'</p>';
									}
									$explanation .= '</ul>';
								} else {
									$explanation .= '<p>'.$subsegment.'</p>';
								}
							}
						}
						$explanation .= '</div>';
						// print_r($explanation);
						// die();
						$data['explanation'][$key1] = $explanation;
					}
				}
			} else {
				$data['explanation'] = array($data['explanation']);
			}
		} else {
			$data['explanation'] =  NULL;
		}
		$data['hints_all'] = count($data['explanation']);
		$data['hints_used'] = 0;

		// Should hints be replaced?
		if (!isset($data['hint_replace'])) {
			$data['hint_replace'] = FALSE;
		}

		return $data;
	}

	/**
	 * Get answer length
	 *
	 * Calculates maximum length of answers from options
	 *
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (completed)
	 */
	public function getColumnWidth($data) {

		$lengths = [];

		foreach ($data['options'] as $option) {

			$lengths[] = count(str_split($option));
		}

		$max_length = max($lengths);
		$min_length = min($lengths);

		if ($max_length < 2) {
			$width = 2;
		} elseif ($max_length < 10) {
			$width = 4;
		} elseif ($max_length < 20) {
			$width = 6;
		} else {
			$width = 8;
		}

		$data['align'] = ($max_length == $min_length ? 'center' : 'left');
		$data['width'] = $width;

		return $data;
	}
}

?>