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
		$data['type'] 		= 'main';
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

			$data['prev'] = $this->Database->SubtopicLink($id-1);
			$data['next'] = $this->Database->SubtopicLink($id+1);

		} elseif ($type == 'exercise') {

			$data['prev'] = $this->Database->ExerciseLink($id-1, 'navigacio');
			$data['next'] = $this->Database->ExerciseLink($id+1, 'navigacio');

		}
		
		$data['results'] = $this->Session->GetResults();

		return $data;
	}

	/**
	 * Get subtopic data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 *
	 * @return array $data Subtopic data
	 */
	public function SubtopicData($classlabel, $subtopiclabel) {

		$subtopicID = $this->Database->SubtopicID($classlabel, $subtopiclabel);

		if ($subtopicID) {

			$data['type'] 		= 'subtopic';
			$data['exercises']	= $this->SubtopicExercises($classlabel, $subtopiclabel, $subtopicID);
			$data['results']	= $this->Session->GetResults('subtopic', $subtopiclabel);
			$data['breadcrumb'] = $this->BreadCrumb('subtopic', $subtopicID);
			$data['title']		= $this->Database->SubtopicTitle($subtopicID);

		} else {

			$data = NULL;

		}

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 * @param int    $exerciseID    Exercise ID
	 *
	 * @return array $data Exercise data
	 */
	public function ExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID) {

		$data['type'] 		= 'exercise';
		$data['results'] 	= $this->Session->GetResults();
		$data['exercise'] 	= $this->GetExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID);
		$data['breadcrumb'] = $this->BreadCrumb('exercise', $exerciseID);

		$data['results']['id'] = $exerciseID;
		$data['results']['type'] = 'exercise';

		$data['progress'] 	= $this->Session->UserProgress($exerciseID);

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

		$this->db->order_by('id', 'desc');
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

							$exercises 	= $this->db->get_where('exercises', array('subtopicID' => $subtopic->id));
							$exercises_menu 		= [];
							$subtopic_menu['show']	= FALSE;
							$exercises_no			= 0;

							foreach ($exercises->result() as $exercise) {

								$exercise_menu['label'] 	= $exercise->label;
								$exercise_menu['link'] 		= $this->Database->ExerciseLink($exercise->id, 'fooldal');
								$exercise_menu['name'] 		= $exercise->name;
								$exercise_menu['status'] 	= $exercise->status;
								$exercise_menu['ex_order'] 	= $exercise->ex_order;
								$exercise_menu['difficulty']= $exercise->difficulty;

								if ($this->Session->CheckLogin() || $exercise->status == 'OK') {

									$exercise_menu['show'] 	= TRUE;
									$subtopic_menu['show'] 	= TRUE;
									$topic_menu['show'] 	= TRUE;
									$class_menu['show'] 	= TRUE;
									$exercises_no++;

								} else {

									$exercise_menu['show'] 	= FALSE;

								}

								$exercises_menu[] = $exercise_menu;
							}

							$subtopic_menu['label'] 		= $subtopic->label;
							$subtopic_menu['name'] 			= $subtopic->name;
							$subtopic_menu['exercises'] 	= $exercises_menu;
							$subtopic_menu['exercise_no']	= $exercises_no;

							$subtopics_menu[] = $subtopic_menu;
						}
					}

					$topic_menu['name'] = $topic->name;
					$topic_menu['subtopics'] = $subtopics_menu;					

					$topics_menu[] = $topic_menu;
				}
			}

			$class_menu['name'] = $class->name;
			$class_menu['label'] = $class->label;
			$class_menu['topics'] = $topics_menu;

			$classes_menu[] = $class_menu;
		}

		$data['classes'] = $classes_menu;
		$data['random_exercises'] = $this->RandomExercises();

		return $data;
	}

	/**
	 * Get random exercises
	 *
	 * Gets link to an easy/medium/hard exercise
	 *
	 * @return array $links Exercise links
	 */
	public function RandomExercises() {

		$this->db->order_by('id', 'random');

		$easy_exercises = $this->db
			->get_where('exercises', ['difficulty' => 1, 'status' => 'OK'])
			->result_array();

		$medium_exercises = $this->db
			->get_where('exercises', ['difficulty' => 2, 'status' => 'OK'])
			->result_array();

		$hard_exercises = $this->db
			->get_where('exercises', ['difficulty' => 3, 'status' => 'OK'])
			->result_array();

		$easy_id 	= $easy_exercises[0]['id'];
		$medium_id 	= $medium_exercises[0]['id'];
		$hard_id 	= $hard_exercises[0]['id'];
		
		$links['easy'] 		= $this->Database->ExerciseLink($easy_id, 'veletlen');
		$links['medium'] 	= $this->Database->ExerciseLink($medium_id, 'veletlen');
		$links['hard'] 		= $this->Database->ExerciseLink($hard_id, 'veletlen');

 		return $links;
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
	 * Get link of next exercise
	 *
	 * Checks whether user has completed all rounds of exercise.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $link Next exercise link
	 */
	public function NextLink($id) {

		$level_max  = $this->Database->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		$id_next = ($level_user < $level_max ? $id : $id+1);

		if ($id == $id_next) {
			$next = $this->Database->ExerciseLink($id_next);
		} else {
			$next = $this->Database->ExerciseLink($id_next, 'kovetkezo');
		}

 		return $next['link'];
	}

	/**
	 * Get exercises of subtopic
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param int    $subtopicID    Subtopic ID
	 *
	 * @return array $data Exercises
	 */
	public function SubtopicExercises($classlabel, $subtopiclabel, $subtopicID) {

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

					$row['status'] 		= $exercise->status;
					$row['label'] 		= $exercise->label;
					$row['name'] 		= $exercise->name;
					$row['complete'] 	= $this->Session->isComplete($exercise->id);
					$row['progress'] 	= $this->Session->UserProgress($exercise->id);
					$row['link'] 		= $this->Database->ExerciseLink($exercise->id, 'temakor');

					$row['classlabel'] 		= $classlabel;
					$row['subtopiclabel'] 	= $subtopiclabel;

					$exercisedata = $this->GetExerciseData($classlabel, $subtopiclabel, $exercise->label, $exercise->id, $save=FALSE);
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
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 * @param int    $exerciseID    Exercise ID
	 * @param bool   $save          Should we save exercise data in session?
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID, $save=TRUE) {

		$this->load->helper('string');

		// Get exercise level
		$level_user = $this->Session->getUserLevel($exerciseID);
		$level_max = $this->Database->getMaxLevel($exerciseID);

		$level = min($level_max, ++$level_user);

		// Generate exercise
		$path = $classlabel.'/'.$subtopiclabel.'/'.$exerciselabel;
		$lib_name = $classlabel.$subtopiclabel.$exerciselabel;
		$this->load->library($path, NULL, $lib_name);

		$data = $this->$lib_name->Generate($level);

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

		$data = $this->AddHints($exerciseID, $data);
		
		$hash = random_string('alnum', 16);

		if ($save) {
			$this->Session->SaveExerciseData($exerciseID, $level, $data, $hash);
		}

		$data['hash']			= $hash;
		$data['classlabel'] 	= $classlabel;
		$data['subtopiclabel'] 	= $subtopiclabel;
		$data['exerciselabel'] 	= $exerciselabel;

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

	/**
	 * Get users' activities
	 *
	 * Collects activities of all users
	 *
	 * @return array $data Subtopic data
	 */
	public function Users() {

		$data['type'] 		= 'stat_users';
		$data['results'] 	= $this->Session->GetResults();

		$this->db->order_by('id', 'desc');
		$users = $this->db->get('users');

		if (count($users->result()) > 0) {

			$user_data = [];

			foreach ($users->result() as $user) {

				$user_menu['id'] 		= $user->id;
				$user_menu['start'] 	= $this->Database->UserSessionStart($user->id);
				$user_menu['duration'] 	= $this->Database->UserDuration($user->id);
				$user_menu['exercises'] = $this->Database->UserExercises($user->id);
				$user_menu['max_level'] = $this->Database->UserMaxLevel($user->id);

				if ($user_menu['duration']) {
					$user_data[] = $user_menu;
				}

			}

			$data['users'] = $user_data;

		} else {

			$data['users'] = [];

		}

		return $data;
	}

	/**
	 * Get user activities
	 *
	 * Collects activities of specific user
	 *
	 * @return array $data Subtopic data
	 */
	public function UserExercises($userID) {

		$data['type'] 		= 'stat_exercises';
		$data['results'] 	= $this->Session->GetResults();

		$users = $this->db->get_where('users', ['id' => $userID]);

		$user = $users->result()[0];

		$exercises = $this->db->get_where('user_exercises', ['userID' => $user->id]);

		if (count($exercises->result()) > 0) {

			foreach ($exercises->result() as $exercise) {

				$exercise_menu['id'] 		= $exercise->exerciseID;
				$exercise_menu['link'] 		= $this->Database->ExerciseLink($exercise->exerciseID);
				$exercise_menu['time'] 		= $this->Database->UserExerciseTime($exercise->id);
				$exercise_menu['actions'] 	= $this->Database->UserExerciseActions($exercise->id);
				$exercise_menu['source'] 	= $exercise->access;

				$exercise_data[] = $exercise_menu;

			}

			$data['exercises'] = $exercise_data;

		} else {

			$data['exercises'] = [];

		}

		return $data;
	}
}

?>