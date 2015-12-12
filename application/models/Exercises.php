<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exercises extends CI_model {

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

		$this->Database->RecordAction($id, $level, $status);
		$this->Session->UpdateExerciseData($status, $hash);
		$this->Session->UpdateResults($id, $level, $status);
		$this->Session->UpdateTodoList($id);

		$levels = $this->Session->getUserLevels($id);
		$id_next = $this->getNextExercise($id);
		$subtopicID = $this->getSubtopicID($id);

		if (!$id_next) {
			$this->Session->CompleteQuest();
		}

		$output = array(
			'status' 		=> $status,
			'message' 		=> $message,
			'submessages'	=> (isset($submessages) ? $submessages : []),
			'levels'		=> $levels,
			'id_next'		=> $id_next,
			'subtopicID'	=> $subtopicID
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

		// $this->session->unset_userdata('exercise');

		$this->load->helper('string');
		$this->load->model('Maths');

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 
		$label 		= $exercise->label;

		$data		= $this->Maths->$label($level);
		$hash		= random_string('alnum', 16);

		$this->SaveToSession($id, $level, $data, $hash);

		$data['level'] 		= $level;
		$data['youtube'] 	= $exercise->youtube;
		$data['id'] 		= $id;
		$data['id_prev']	= $this->IDPrevious($id);
		$data['hash']		= $hash;

		return $data;
	}

	/**
	 * Save exercise data to session
	 *
	 * @param  int    $id    Exercise id
	 * @param  int    $level Exercise level
	 * @param  array  $data  Exercise data
	 * @param  string $hash  Random string
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
	 * Get exercises of subtopic
	 *
	 * @param  int   $id   Subtopic ID
	 * @return array $data Exercises
	 */
	public function getExerciseList($id) {

		$query = $this->db->get_where('exercises', array('subtopicID' => $id));

		$id_next = $this->IDNextSubtopic();
		$data['id_next'] = $id_next;

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
		}

		return $data;
	}

	/**
	 * Get next exercise
	 *
	 * @param  int   $id    Exercise ID
	 * @param  int   $level Exercise level
	 * @return array $data  Next exercise
	 */
	public function getNextExercise($id) {

		$method = $this->session->userdata('method');

		if ($method == 'subtopic') {

			$id_next = $this->IDNextSubtopic();

		} elseif ($method == 'exercise') {

			$id_next = $this->IDNextExercise($id);
		}

 		return $id_next;
	}

	/**
	 * Get maximum level for exercise
	 *
	 * @param  int $id        Exercise ID
	 * @return int $level_max Maximum level
	 */
	public function getMaxLevel($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$level_max 	= $query->result()[0]->level;

 		return $level_max;
	}

	/**
	 * Get subtopicID for exercise
	 *
	 * @param  int $id         Exercise ID
	 * @return int $subtopicID Subtopic ID
	 */
	public function getSubtopicID($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$subtopicID = $query->result()[0]->subtopicID;

 		return $subtopicID;
	}

	/**
	 * Get user level for exercise
	 *
	 * @param  int $id         Exercise ID
	 * @return int $level_user User level
	 */
	public function getUserLevel($id) {

		$results = $this->session->userdata('results');
		$level_user = (isset($results[$id]) ? $results[$id] : 0);

 		return $level_user;
	}

	/**
	 * Get ID of previous exercise
	 *
	 * Offers an easier exercise if user has not solved exercise on the easiest level.
	 *
	 * @param  int $id      Exercise ID
	 * @return int $id_prev ID of previous exercise
	 */
	public function IDPrevious($id) {

		$id_prev 	= NULL;

		$results 	= $this->session->userdata('results');

		if (isset($results[$id]) && $results[$id] > 0) {

			// User has already solved exercise on the easiest level
			return $id_prev;

		} else {

			$query 		= $this->db->get_where('exercises', array('id' => $id));
			$exercise1 	= $query->result()[0];
			$query 		= $this->db->get_where('links', array('exerciseID' => $id));
			$links 		= $query->result();

			if (count($links) > 0) {

				shuffle($links);

				foreach ($links as $link) {

					$query = $this->db->get_where('exercises', array('label' => $link->label));
					$exercise2 = $query->result()[0];
					
					$id2 = $exercise2->id;
					$level_max = $exercise2->level;
					$results = $this->session->userdata('results');

					if (!isset($results[$id2])) {

						// user has not encountered exercise
						$id_prev = $id2;
						break;

					} elseif ($results[$id2] < $level_max) {

						// user has not completed exercise at all levels
						$id_prev = $id2;
						break;

					} else {

						// is user allowed to return fully completed exercises?
						$solved_exercise_allowed = TRUE;
						if ($solved_exercise_allowed) {
							$id_prev = $id2;
						}
					}
				}
			}
		}

 		return $id_prev;
	}

	/**
	 * Get ID of next avaliable exercise (subtopic mode)
	 *
	 * 1. Checks whether user has complated all exercises in the to do list.
	 *    - If not, returns last element of to do list.
	 * 2. Checks whether user has complated all exercises of subtopics.
	 *    - If not, returns link to next available exercise.
	 *    - If so, returns null.
	 *
	 * @return int $id      Id of current subtopic
	 * @return int $id_next Id of next exercise
	 */
	public function IDNextSubtopic($id = NULL) {

		$id_next = NULL;

		$subtopicID = ($id ? $id : $this->session->userdata('goal'));

		$todo_list = $this->session->userdata('todo_list');

		if (count($todo_list) > 0) {

			// User has not finished to do list
			$todo_last = array_slice($todo_list, -1);
			$id_next = $todo_last[0];

		} else {

			$results = $this->session->userdata('results');
			$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
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
		}

 		return $id_next;
	}

	/**
	 * Get ID of next avaliable exercise (exercise mode)
	 *
	 * 1. Checks whether user has complated exercise in all level.
	 *    - If not, returns same exercise.
	 * 2. Checks whether user has any exercise in to do list.
	 *    - If so, returns last exercise of list.
	 *    - If not, returns null.
	 *
	 * @param  int   $id Exercise ID
	 * @return array $data Exercise data
	 */
	public function IDNextExercise($id) {

		$id_next = NULL;

		$goal = $this->session->userdata('goal');

		$level_max  = $this->getMaxLevel($id);
		$level_user = $this->getUserLevel($id);

		// print_r($level_max);

		if ($level_user < $level_max) {

			// User has not solved exercise at all levels
			$id_next = $id;

 		} else {

 			$todo_list = $this->session->userdata('todo_list');

			if (!empty($todo_list)) {

				// User has not finished to do list
				$todo_last = array_slice($todo_list, -1);
				$id_next = $todo_last[0];
			}
		}

 		return $id_next;
	}
}

?>