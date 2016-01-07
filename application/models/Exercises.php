<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exercises extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		parent::__construct();

		$this->load->helper('url');
		defined('RESOURCES_URL') OR define('RESOURCES_URL', base_url('resources/exercises'));

		return;
	}

	/**
	 * Check answer
	 *
	 * @param  array $jsondata Answer data
	 * @return void
	 */
	public function CheckAnswer($jsondata) {

		$this->load->model('Session');
		$this->load->model('Database');


		$answerdata = json_decode($jsondata, TRUE);
		list($answer, $hash) = $this->GetAnswerData($answerdata);
		list($correct, $solution, $level, $type, $id) = $this->Session->GetExerciseData($hash);

		switch ($type) {

			case 'int':

				list($status, $message) = $this->GenerateMessagesInt($answer, $correct, $solution);

				break;

			case 'quiz':
				list($status, $message) = $this->GenerateMessagesQuiz($answer, $correct, $solution);
				break;

			case 'multi':
				list($status, $message, $submessages) = $this->GenerateMessagesMulti($answer, $correct, $solution);
				break;
		}

		$this->Session->UpdateExerciseData($status, $hash);
		$this->Session->UpdateResults($id, $level, $status);

		$levels = $this->getUserLevels($id);
		$id_next = $this->getIDNext($id);

		$questID = $this->getQuestID($id);
		$subtopicID = $this->getSubtopicID($id);

		$output = array(
			'status' 		=> $status,
			'message' 		=> $message,
			'submessages'	=> (isset($submessages) ? $submessages : []),
			'levels'		=> $levels,
			'id_next'		=> $id_next,
			'subtopicID'	=> $subtopicID,
			'questID'		=> $questID,
			'session' 		=> json_encode($_SESSION)
		);

		return $output;
	}

	/**
	 * Get answer data
	 *
	 * @param array $json Answer data (JSON)
	 *
	 * @return array  $answer Answer data (array)
	 * @return string $hash   Random string
	 */
	public function GetAnswerData($json) {

		// Collect answer data
		$answer = [];

		foreach ($json as $item) {
			switch ($item['name']) {
				case 'answer':
					$answer[] = $item['value'];
					break;
				case 'hash':
					$hash = $item['value'];
					break;
			}
		}

		return array($answer, $hash);
	}

	/**
	 * Generate messages for integer type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status  Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message Message
	 */
	public function GenerateMessagesInt($answer, $correct, $solution) {

		if ($answer[0] == '') {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif ($answer[0] == $correct) {
			$status = 'CORRECT';
			$message = 'Helyes válasz!';
		} else {
			$status = 'WRONG';
			$message = 'A helyes válasz: '.$solution;
		}

		return array($status, $message);
	}

	/**
	 * Generate messages for quiz type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status  Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message Message
	 */
	public function GenerateMessagesQuiz($answer, $correct, $solution) {

		if (!isset($answer[0])) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif ($answer[0] == $correct) {
			$status = 'CORRECT';
			$message = 'Helyes válasz!';
		} else {
			$status = 'WRONG';
			$message = 'Hibás válasz!';
		}

		return array($status, $message);
	}

	/**
	 * Generate messages for multiple choice type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesMulti($answer, $correct, $solution) {

		$submessages = [];

		if (count($answer) == 0) {
			$status = 'NOT_DONE';
			$message = 'Jelölj be legalább egy választ!';
		} else {
			$status = 'CORRECT';
			$message = 'Helyes válasz!';
			foreach ($correct as $key => $value) {
				$submessages[$key] = 'WRONG';
				if ($value == 1) {
					if (in_array($key, $answer)) {
						$submessages[$key] = 'CORRECT';
					} else {
						$status = 'WRONG';
						$message = 'Hibás válasz!';
					}
				} else {
					if (in_array($key, $answer)) {
						$status = 'WRONG';
						$message = 'Hibás válasz!';
					} else {
						$submessages[$key] = 'CORRECT';
					}
				}
			}
		}

		return array($status, $message, $submessages);
	}

	/**
	 * Get exercise data
	 *
	 * @param  int   $id    Exercise ID
	 * @param  int   $level Exercise level
	 * @return array $data  Exercise data
	 */
	public function getExerciseData($id, $level) {

		$this->session->unset_userdata('exercise');

		$this->load->helper('string');
		$this->load->helper('Maths');

		$this->load->model('Maths');
		$this->load->model('Database');

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 

		$quizdata = $this->Database->getQuizData($id);

		if ($quizdata) {

			$data = $this->getQuizData($quizdata);

		} else {

			$function = $exercise->label;

			$class = $this->Database->getClassLabel($id);
			$topic = $this->Database->getTopicLabel($id);

			if (!function_exists($function)) {
				$this->load->helper('Exercises/'.$class.'/'.$topic. '/functions');
			}

			$data = $function($level);

			if ($data['type'] == 'quiz') {
				$data = $this->getAnswerLength($data);
			}
		}

		$hash = random_string('alnum', 16);

		$this->SaveToSession($id, $level, $data, $hash);

		$data['level'] 		= $level;
		$data['youtube'] 	= $exercise->youtube;
		$data['download'] 	= $exercise->download;
		$data['id'] 		= $id;
		$data['hash']		= $hash;

		return $data;
	}

	/**
	 * Get quiz data
	 *
	 * Define correct answer, solution and options for quiz
	 *
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (completed)
	 */
	public function getQuizData($data) {

		$length = max(count(str_split($data['correct'])),
					  count(str_split($data['wrong1'])),
					  count(str_split($data['wrong2'])));

		$options = array(
			$data['correct'],
			$data['wrong1'],
			$data['wrong2']
		);

		shuffle($options);
		$correct = array_search($data['correct'], $options);

		return array(
			'question' 	=> $data['question'],
			'options' 	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $options[$correct],
			'type' 		=> 'quiz',
			'length' 	=> $length
		);
	}

	/**
	 * Get answer length
	 *
	 * Calculates maximum length of answers from options (to choose best display)
	 *
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (completed)
	 */
	public function getAnswerLength($data) {

		$length = 0;

		foreach ($data['options'] as $option) {

			$length = max($length, count(str_split($option)));
		}

		$data['length'] = $length;

		return $data;
	}
	/**
	 * Save exercise data to session
	 *
	 * @param int    $id    Exercise id
	 * @param int    $level Exercise level
	 * @param array  $data  Exercise data
	 * @param string $hash  Random string
	 *
	 * @return void
	 */
	public function SaveToSession($id, $level, $data, $hash) {

		$sessiondata 		= $this->session->userdata('exercise');
		
		$answer['id']		= $id;
		$answer['level'] 	= $level;
		$answer['correct'] 	= $data['correct'];
		$answer['type'] 	= $data['type'];
		$answer['solution']	= $data['solution'];

		$sessiondata[$hash] = $answer;

		$this->session->set_userdata('exercise', $sessiondata);

		return;
	}

	/**
	 * Get quests of subtopic
	 *
	 * @param int $subtopicID Subtopic ID
	 *
	 * @return array $data Quests
	 */
	public function getSubtopicQuests($subtopicID) {

		$query = $this->db->get_where('quests', array('subtopicID' => $subtopicID));
		$quests = $query->result();

		if (count($quests) > 0) {
			foreach ($quests as $quest) {

				$questID = $quest->id;

				$row['id'] 			= $questID;
				$row['name'] 		= $quest->name;
				$row['exercises'] 	= $this->getQuestExercises($questID);
				$row['id_next']		= $this->IDNextQuest($questID);

				$data['quests'][] 	= $row;
			}
		}

		return $data;
	}

	/**
	 * Get exercises of quest
	 *
	 * @param int $id Quest ID
	 *
	 * @return array $data Exercises
	 */
	public function getQuestExercises($id) {

		$query = $this->db->get_where('exercises', array('questID' => $id));

		$exercises = $query->result();

		if (count($exercises) > 0) {
			foreach ($exercises as $exercise) {

				$id = $exercise->id;

				$row['levels'] 	= $this->getUserLevels($id);
				$row['id'] 		= $id;
				$row['name'] 	= $exercise->name;

				$data[] = $row;
			}
		}

		return $data;
	}

	/**
	 * Get id of next exercise
	 *
	 * @param int $id    Exercise ID
	 * @param int $level Exercise level
	 *
	 * @return array $id_next Id of next exercise
	 */
	public function getIDNext($id) {

		$method = $this->session->userdata('method');

		if ($method == 'quest') {

			$questID = $this->session->userdata('goal');
			$id_next = $this->IDNextQuest($questID);

		} elseif ($method == 'exercise') {

			$id_next = $this->IDNextExercise($id);

		}

 		return $id_next;
	}

	/**
	 * Get maximum level for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $level_max Maximum level
	 */
	public function getMaxLevel($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$level_max 	= $query->result()[0]->level;

 		return $level_max;
	}

	/**
	 * Get exercise name
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $name Exercise name
	 */
	public function getExerciseName($id) {

		$query 	= $this->db->get_where('exercises', array('id' => $id));
		$name 	= $query->result()[0]->name;

 		return $name;
	}

	/**
	 * Get quest ID for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $questID Quest ID
	 */
	public function getQuestID($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$questID 	= $query->result()[0]->questID;

		return $questID;
	}

	/**
	 * Get subtopic ID for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $subtopicID Subtopic ID
	 */
	public function getSubtopicID($id) {

		$this->db->select('subtopicID')
				->distinct()
				->from('quests')
				->join('exercises', 'quests.id = exercises.questID', 'inner')
				->where('exercises.id', $id);
		$query = $this->db->get();
		$subtopicID = $query->result()[0]->subtopicID;

 		return $subtopicID;
	}

	/**
	 * Get user level for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $level_user User level
	 */
	public function getUserLevel($id) {

		$results = $this->session->userdata('results');
		$level_user = (isset($results[$id]) ? $results[$id] : 0);

 		return $level_user;
	}

	/**
	 * Get user levels for current exercise
	 *
	 * Returns an array with 0s and 1s. 1 means the user has answered the exercise
	 * at the specific level correctly, O means the opposite. Data is used to update 
	 * star icons with javascript.
	 *
	 * @param  int 	 $id     Exercise ID
	 * @return array $levels Exercise levels (0 or 1)
	 */
	public function getUserLevels($id) {

		$max_level = $this->getMaxLevel($id);
		$user_level = $this->getUserLevel($id);

		for ($i=1; $i <= $max_level; $i++) {
			$levels[$i] = ($i <= $user_level ? 1 : 0);
		}

		return $levels;
	}

	/**
	 * Get ID of next avaliable exercise (quest mode)
	 *
	 * Checks whether user has completed all exercises of quest. If not,
	 * returns link to next available exercise else returns null.
	 *
	 * @param int $questID Id of current quest
	 *
	 * @return int $id_next Id of next exercise
	 */
	public function IDNextQuest($questID) {

		$id_next = NULL;

		$results = $this->session->userdata('results');
		$query = $this->db->get_where('exercises', array('questID' => $questID));
		$exercises = $query->result();

		foreach ($exercises as $exercise) {

			$id = $exercise->id;

			if (isset($results[$id])) {

				$level_max  = $this->getMaxLevel($id);
				$level_user = $this->getUserLevel($id);

				if ($level_user < $level_max) {

					// User has not solved exercise at all levels
					$id_next = $id;
					break;
				}
				
			} else {

				// User has not solved exercise yet
				$id_next = $id;
				break;
			}
		}

 		return $id_next;
	}

	/**
	 * Get ID of next exercise (exercise mode)
	 *
	 * Checks whether user has completed exercise in all level. If not,
	 * returns same exercise else returns null.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $data Exercise data
	 */
	public function IDNextExercise($id) {

		$id_next = NULL;

		$goal = $this->session->userdata('goal');

		$level_max  = $this->getMaxLevel($id);
		$level_user = $this->getUserLevel($id);

		if ($level_user < $level_max) {

			// User has not solved exercise at all levels
			$id_next = $id;

 		} else {

 			$id_next = NULL;
		}

 		return $id_next;
	}
}

?>