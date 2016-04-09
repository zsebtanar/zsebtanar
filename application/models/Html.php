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
	 * @return array $data Main page data
	 */
	public function MainData() {

		$data['maindata'] 	= $this->GetMainData();
		$data['latest']		= $this->Database->getLatest();
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
	 * @param string $hash Exercise hash
	 *
	 * @return array $data Breadcrumb data
	 */
	public function BreadCrumb($type, $id, $hash=NULL) {

		if ($type == 'subtopic') {

			$data['prev'] = $this->Database->SubtopicLink($id-1);
			$data['next'] = $this->Database->SubtopicLink($id+1);

		} elseif ($type == 'exercise') {

			$data['prev'] = $this->Database->ExerciseLink($id-1, $hash);
			$data['next'] = $this->Database->ExerciseLink($id+1, $hash);

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
	public function SubtopicData($subtopicID=NULL) {

		$data['type'] 		= 'subtopic';
		$data['exercises']	= $this->SubtopicExercises($subtopicID);
		$data['results']	= $this->Session->GetResults('subtopic', $subtopicID);
		$data['breadcrumb'] = $this->BreadCrumb('subtopic', $subtopicID);
		$data['title']		= $this->Database->SubtopicTitle($subtopicID);

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $data Exercise data
	 */
	public function ExerciseData($id) {

		$data['type'] 		= 'exercise';
		$data['results'] 	= $this->Session->GetResults('exercise', $id);
		$data['exercise'] 	= $this->GetExerciseData($id);
		$data['breadcrumb'] = $this->BreadCrumb('exercise', $id, $data['exercise']['hash']);

		$data['results']['id'] = $id;
		$data['results']['type'] = 'exercise';

		$data['progress'] 	= $this->Session->UserProgress($id);

		return $data;
	}

	/**
	 * Get data for main page
	 *
	 * Gets all classes, topics & subtopics
	 *
	 * @return array $data Subtopics
	 */
	public function GetMainData() {

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
							$subtopic_menu['label'] = $subtopic->label;
							$subtopic_menu['name'] 	= $subtopic->name;
							$subtopic_menu['show']	= FALSE;

							$exercises = $this->db->get_where('exercises', array('subtopicID' => $subtopic->id));
							$subtopic_menu['exercises'] = count($exercises->result());

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

					$topics_menu[] = $topic_menu;
				}
			}

			$class_menu['id'] = $class->id;
			$class_menu['name'] = $class->name;
			$class_menu['label'] = $class->label;
			$class_menu['topics'] = $topics_menu;

			$classes_menu[] = $class_menu;
		}

		$data['classes'] = $classes_menu;

		return $data;
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
	 * Get label of next exercise
	 *
	 * Checks whether user has completed all rounds of exercise.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $label Next exercise label
	 */
	public function NextLabel($id) {

		$level_max  = $this->Database->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		$id_next = ($level_user < $level_max ? $id : $id+1);

		$label = $this->Database->ExerciseLabel($id_next);

 		return $label;
	}

	/**
	 * Get exercises of subtopic
	 *
	 * @param int $subtopicID Subtopic ID
	 *
	 * @return array $data Exercises
	 */
	public function SubtopicExercises($subtopicID=NULL) {

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
					$row['label'] 		= $exercise->label;
					$row['name'] 		= $exercise->name;
					$row['complete'] 	= $this->Session->isComplete($id);
					$row['progress'] 	= $this->Session->UserProgress($id);

					$exercisedata = $this->GetExerciseData($id, $save=FALSE);
					$row['question']	= $exercisedata['question'];

					$data[] = $row;

				}
			}
		}

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * @param int  $id    Exercise ID
	 * @param bool $save  Should we save exercise data in session?
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($id, $save=TRUE) {

		$this->load->helper('string');

		// Get exercise level
		$level_user = $this->Session->getUserLevel($id);
		$level_max = $this->Database->getMaxLevel($id);

		$level = min($level_max, ++$level_user);

		// Generate exercise
		$label = $this->Database->ExerciseLabel($id);

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
		} elseif (($data['type'] == 'array'
				|| $data['type'] == 'list')
				&& !isset($data['labels'])) {
			$data['labels'] = array_fill(0, count($data['correct']), NULL);
		}

		$data = $this->AddHints($id, $data);
		
		$hash = random_string('alnum', 16);

		if ($save) {
			$this->Session->SaveExerciseData($id, $level, $data, $hash);
		}

		$data['level'] 		= $level;
		$data['label'] 		= $label;
		$data['hash']		= $hash;
		$data['subtopiclabel'] = $this->Database->getSubtopicLabel($id);

		return $data;
	}

	/**
	 * Add hints to exercise (if there is none)
	 *
	 * @param int   $id   Exercise id
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (with hints)
	 */
	public function AddHints($id, $data) {

		$hints = [];
		if (isset($data['hints'])) {
			if (is_array($data['hints'])) {

				// Is there more page?
				$multipage = TRUE;
				foreach ($data['hints'] as $value) {
					if (!is_array($value)) {
						$multipage = FALSE;
					}
				}

				// Create multipage hints
				if ($multipage) {
					foreach ($data['hints'] as $page) {
						$page = $this->AddHintPage($page);
						$hints = array_merge($hints, $page);
						
					}
				} else {

					$page = $this->AddHintPage($data['hints']);
					$hints = array_merge($hints, $page);
					// print_r($hints);

				}

			} else {

				// Single hints
				$page = $this->AddHintPage($data['hints']);
				$hints = array_merge($hints, $page);

			}
		} else {

			// No hints
			$hints =  NULL;

		}

		$data['hints']		= $hints;
		$data['hints_all'] 	= count($hints);
		$data['hints_used'] = 0;

		return $data;
	}

	/**
	 * Add page to hints
	 *
	 * @param array $page Hints data
	 *
	 * @return array $page_new Hints data (restructured)
	 */
	public function AddHintPage($page) {

		// Details
		foreach ($page as $key1 => $segment) {
			if (is_array($segment)) {
				$details = $this->AddHintDetails($segment);
				if ($key1 > 0) {
					$page[$key1-1] .= '<div><button class="pull-right btn btn-default btn-details" data-toggle="collapse" data-target="#hint_details'.$key1.'">'
						.'Részletek</button></div><br/>'
						.'<div id="hint_details'.$key1.'" class="collapse well well-sm small">'.$details.'</div>';
				} else {
					print_r('Az útmutató szerkezete hibás!');
				}
				unset($page[$key1]);
			}
		}

		// Restructure
		array_values($page);
		for ($i=0; $i < count($page); $i++) { 
			$hint = '';
			for ($j=0; $j <= $i; $j++) { 
				$hint .= '<p>'.strval($page[$j]).'</p>';
			}
			$page_new[] = $hint;
		}

		return $page_new;
	}

	/**
	 * Add details to hints
	 *
	 * @param array $subsegment Hints data
	 *
	 * @return string $details Hints data (modified)
	 */
	public function AddHintDetails($subsegment) {

		$details = '';
		foreach ($subsegment as $subsubsegment) {
			if (is_array($subsubsegment)) {
				print_r('Hiba az útmutatóban!');
				break;
			}
			$details .= '<p>'.strval($subsubsegment).'</p>';
		}

		return $details;
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
		} elseif ($max_length < 26) {
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