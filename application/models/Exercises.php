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

		$answerdata = json_decode($jsondata, TRUE);

		$data = $this->ConvertAnswerToArray($answerdata);

		$data['submessages'] = [];

		switch ($data['type']) {

			case 'int':
				$data = $this->GenerateMessagesInt($data);
				break;

			case 'quiz':
				$data = $this->GenerateMessagesQuiz($data);
				break;

			case 'multi':
				$data = $this->GenerateMessagesMulti($data);
				break;
		}

		$this->load->model('Session');

		$this->Session->RecordAction($data['id'], $data['level'], $data['status']);
		$this->Session->UpdateResults($data['id'], $data['level'], $data['status']);;
		$this->Session->UpdateTodoList($data['id']);

		$levels = $this->Session->getUserLevels($data['id']);
		$id_next = $this->getNextExercise($data['id']);
		$subtopicID = $this->getSubtopicID($data['id']);
		$goal = $this->session->userdata('goal');

		if (!$id_next) {
			$this->Session->CompleteQuest();
		}

		$output = array(
			'status' 		=> $data['status'],
			'message' 		=> $data['message'],
			'submessages'	=> $data['submessages'],
			'levels'		=> $levels,
			'id_next'		=> $id_next,
			'goal'			=> $goal
		);

		return $output;
	}

	/**
	 * Convert answer to array
	 *
	 * @param  array $json   Answer data (JSON)
	 * @return array $output Answer data (array)
	 */
	public function ConvertAnswerToArray($json) {

		$answer = [];

		foreach ($json as $item) {
			switch ($item['name']) {
				case 'type':
					$type = $item['value'];
					break;
				case 'answer':
					$answer[] = $item['value'];
					break;
				case 'correct':
					$correct = json_decode($item['value'], TRUE);
					break;
				case 'solution':
					$solution = $item['value'];
					break;
				case 'id':
					$id = $item['value'];
					break;
				case 'level':
					$level = $item['value'];
					break;
			}
		}

		$output = array(
			'answer'	=> $answer,
			'type'		=> $type,
			'correct'	=> $correct,
			'solution'	=> $solution,
			'id'		=> $id,
			'level'		=> $level
		);

		return $output;
	}

	/**
	 * Generate messages for integer type exercises
	 *
	 * @param  array $data Answer data
	 * @return array $data Answer data (modified)
	 */
	public function GenerateMessagesInt($data) {

		if ($data['answer'][0] == '') {
			$data['status'] = 'NOT_DONE';
			$data['message'] = 'Hiányzik a válasz!';
		} elseif ($data['answer'][0] == $data['correct']) {
			$data['status'] = 'CORRECT';
			$data['message'] = 'Helyes válasz!';
		} else {
			$data['status'] = 'WRONG';
			$data['message'] = 'A helyes válasz: '.$data['solution'];
		}

		return $data;
	}

	/**
	 * Generate messages for quiz type exercises
	 *
	 * @param  array $data Answer data
	 * @return array $data Answer data (modified)
	 */
	public function GenerateMessagesQuiz($data) {

		if (!isset($data['answer'][0])) {
			$data['status'] = 'NOT_DONE';
			$data['message'] = 'Hiányzik a válasz!';
		} elseif ($data['answer'][0] == $data['correct']) {
			$data['status'] = 'CORRECT';
			$data['message'] = 'Helyes válasz!';
		} else {
			$data['status'] = 'WRONG';
			$data['message'] = 'Hibás válasz!';
		}

		return $data;
	}

	/**
	 * Generate messages for multiple choice type exercises
	 *
	 * @param  array $data Answer data
	 * @return array $data Answer data (modified)
	 */
	public function GenerateMessagesMulti($data) {

		if (count($data['answer']) == 0) {
			$data['status'] = 'NOT_DONE';
			$data['message'] = 'Jelölj be legalább egy választ!';
		} else {
			$data['status'] = 'CORRECT';
			$data['message'] = 'Helyes válasz!';
			foreach ($data['correct'] as $key => $value) {
				$data['submessages'][$key] = 'WRONG';
				if ($value == 1) {
					if (in_array($key, $data['answer'])) {
						$data['submessages'][$key] = 'CORRECT';
					} else {
						$data['status'] = 'WRONG';
						$data['message'] = 'Hibás válasz!';
					}
				} else {
					if (in_array($key, $data['answer'])) {
						$data['status'] = 'WRONG';
						$data['message'] = 'Hibás válasz!';
					} else {
						$data['submessages'][$key] = 'CORRECT';
					}
				}
			}
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

		$this->load->model('Maths');

		$data 				= $this->Maths->$label($level);
		$data['level'] 		= $level;
		$data['youtube'] 	= $exercise->youtube;
		$data['id'] 		= $id;
		$data['id_prev']	= $this->IDPrevious($id);

		return $data;
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
	 * @param  int $id      Exercise ID
	 * @return int $id_prev ID of previous exercise
	 */
	public function IDPrevious($id) {

		$id_prev 	= NULL;

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