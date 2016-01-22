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
		list($correct, $explanation, $solution, $level, $type, $id) = $this->Session->GetExerciseData($hash);

		list($status, $message, $submessages) = $this->GenerateMessages($type, $answer, $correct, $solution);

		$this->Session->DeleteExerciseData($status, $hash);

		if ($status == 'CORRECT') {
			$message = $this->Session->UpdateResults($id, $message);
		}

		$id_next 	= $this->getIDNext($id);
		$questID 	= $this->getQuestID($id);
		$subtopicID = $this->getSubtopicID($id);
		$level_user = $this->Session->getUserLevel($id);
		$level_max 	= $this->Exercises->getMaxLevel($id);
		$progress 	= $this->Session->getUserProgress($id);

		$output = array(
			'status' 		=> $status,
			'level_max' 	=> $level_max,
			'level_user' 	=> $level_user,
			'message' 		=> $message,
			'submessages'	=> $submessages,
			'id_next'		=> $id_next,
			'subtopicID'	=> $subtopicID,
			'explanation'	=> $explanation,
			'questID'		=> $questID,
			'progress'		=> $progress
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
	 * Generate messages for exercises
	 *
	 * @param string $type     Exercise type
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status  Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message Message
	 */
	public function GenerateMessages($type, $answer, $correct, $solution) {

		switch ($type) {

			case 'int':
				list($status, $message) = $this->GenerateMessagesInt($answer, $correct, $solution);
				break;

			case 'text':
				list($status, $message) = $this->GenerateMessagesText($answer, $correct, $solution);
				break;

			case 'quiz':
				list($status, $message) = $this->GenerateMessagesQuiz($answer, $correct, $solution);
				break;

			case 'multi':
				list($status, $message, $submessages) = $this->GenerateMessagesMulti($answer, $correct, $solution);
				break;

			case 'division':
				list($status, $message) = $this->GenerateMessagesDivision($answer, $correct, $solution);
				break;
		}

		$submessages = (isset($submessages) ? $submessages : []);

		return array($status, $message, $submessages);
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
	 * Generate messages for text type exercises
	 *
	 * @param array  $answer   User answer
	 * @param string $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status  Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message Message
	 */
	public function GenerateMessagesText($answer, $correct, $solution) {

		if ($answer[0] == '') {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif (strtoupper($answer[0]) == $correct) {
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
			$message = 'A helyes válasz: '.$solution;
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
	 * Generate messages for division type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesDivision($answer, $correct, $solution) {

		if ($answer[0] == NULL && $answer[1] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} else {

			list($quotient, $remain) = json_decode($correct);

			if ($quotient != $answer[0] || $remain != $answer[1]) {
				$status = 'WRONG';
				$message = 'A helyes válasz: '.$solution;
			} else {
				$status = 'CORRECT';
				$message = 'Helyes válasz!';
			}
		}

		return array($status, $message);
	}

	/**
	 * Get exercise data
	 *
	 * @param int $id    Exercise ID
	 * @param int $level Exercise level
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($id, $level) {

		// $this->session->unset_userdata('exercise');

		$this->load->helper('string');
		$this->load->helper('Maths');

		$this->load->model('Maths');
		$this->load->model('Database');

		if (!$level) {
			$level = $this->Session->getExerciseLevelNext($id);
		}

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 

		$function = $exercise->label;

		$class = $this->Database->getClassLabel($id);
		$topic = $this->Database->getTopicLabel($id);
		$subtopic = $this->Database->getSubtopicLabel($id);

		if (!function_exists($function)) {
			$this->load->helper('Exercises/'.$class.'/'.$topic.'/'.$subtopic.'/functions');
		}

		$data = $function($level);

		if (!isset($data['type'])) {
			$data['type'] = 'int';
		} elseif ($data['type'] == 'quiz') {
			$data = $this->getAnswerLength($data);
		}

		$data = $this->AddExplanation($id, $data);

		$hash = random_string('alnum', 16);

		$this->Session->SaveExerciseData($id, $level, $data, $hash);

		$data['level'] 		= $level;
		$data['youtube'] 	= $exercise->youtube;
		$data['hint'] 		= $exercise->hint;
		$data['id'] 		= $id;
		$data['hash']		= $hash;
		$data['subtopicID'] = $this->getSubtopicID($id);

		return $data;
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
				$explanation = '<ul>';
				foreach ($data['explanation'] as $segment) {
					if (is_array($segment)) {
						$explanation .= '<ul>';
						foreach ($segment as $subsegment) {
							$explanation .= '<li>'.$subsegment.'</li>';
						}
						$explanation .= '</ul>';
					} else {
						$explanation .= '<li>'.$segment.'</li>';
					}
				}
				$explanation .= '</ul>';
				$data['explanation'] = $explanation;
			}
		} elseif ($this->hasHint($id)) {

			$data['explanation'] = 'Segítségre van szükséged? Kattints a <img src="'.base_url().'assets/images/light_bulb.png" alt="hint" width="40">-ra!';

		}

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
	 * Get quests of subtopic
	 *
	 * @param int $subtopicID Subtopic ID
	 *
	 * @return array $data Quests
	 */
	public function getSubtopicQuests($subtopicID) {

		$query = $this->db->get_where('quests', array('subtopicID' => $subtopicID));
		$quests = $query->result();
		$data = NULL;

		if (count($quests) > 0) {
			foreach ($quests as $quest) {

				$questID = $quest->id;

				$row['id'] 			= $questID;
				$row['name'] 		= $quest->name;
				$row['exercises'] 	= $this->getQuestExercises($questID);
				$row['links'] 		= $this->getQuestLinks($questID);
				$row['complete'] 	= $this->isComplete($questID);

				if ($row['exercises']) {
					$data['quests'][] 	= $row;
				}
			}
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
	public function getAnswerLength($data) {

		$length = 0;

		foreach ($data['options'] as $option) {

			$length = max($length, count(str_split($option)));
		}

		$data['length'] = $length;

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

		$data = NULL;

		$exercises = $query->result();

		if (count($exercises) > 0) {
			foreach ($exercises as $exercise) {
				if ((NULL !== $this->session->userdata('Logged_in')
					&& $this->session->userdata('Logged_in'))
					|| $exercise->status == 'OK') {

					$id = $exercise->id;

					$row['userlevel'] 	= $this->Session->getUserLevel($id);
					$row['maxlevel'] 	= $this->getMaxLevel($id);
					$row['id'] 			= $id;
					$row['name'] 		= $exercise->name;
					$row['status'] 		= $exercise->status;

					$data[] = $row;
				}
			}
		}

		return $data;
	}

	/**
	 * Get links of quest
	 *
	 * @param int $id Quest ID
	 *
	 * @return array $data Links
	 */
	public function getQuestLinks($id) {

		$data = NULL;

		$query = $this->db->get_where('links', array('questID' => $id));
		$links = $query->result();
		
		foreach ($links as $link) {

			$label 			= $link->label;
			$query 			= $this->db->get_where('quests', array('label' => $label));

			$questID		= $query->result()[0]->id;

			$row['questID']	= $questID;
			$row['name'] 	= $query->result()[0]->name;

			$row['subtopicID']	= $query->result()[0]->subtopicID;
			$row['complete'] 	= $this->isComplete($questID);

			$data[] = $row;
		}

		return $data;
	}

	/**
	 * Check if quest is completed
	 *
	 * @param int $id Quest ID
	 *
	 * @return bool $isComplete Is quest completed?
	 */
	public function isComplete($questID) {

		$quests = $this->session->userdata('quests');

		$isComplete	= (isset($quests[$questID]) ? $quests[$questID] : FALSE);

 		return $isComplete;
	}

	/**
	 * Check if exercise has hint
	 *
	 * @param int $id Exercise ID
	 *
	 * @return bool $hasHint Whether exercise has hint
	 */
	public function hasHint($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise = $query->result()[0];

		$hasHint = !($exercise->hint == NULL);

 		return $hasHint;
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
	 * Get number or rounds for exercise
	 *
	 * $rounds shows how many times user needs to solve the exercise to complete it.
	 * If user is logged in, it is only 3 (for debugging purposes). 
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $rounds Maximum rounds
	 */
	public function getMaxRound($id) {

		if (NULL !== $this->session->userdata('Logged_in')
			&& $this->session->userdata('Logged_in')) {

			$rounds = 3;

		} else {

			$level_max	= $this->getMaxLevel($id);
			$query 		= $this->db->get_where('exercises', array('id' => $id));
			$round 	 	= $query->result()[0]->rounds;

			$rounds = $level_max*$round;

		}

 		return $rounds;
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
	 * Get subtopic name for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $name Subtopic name
	 */
	public function getSubtopicName($id) {

		$this->db->select('subtopics.name')
				->distinct()
				->from('subtopics')
				->join('quests', 'subtopics.id = quests.subtopicID', 'inner')
				->join('exercises', 'quests.id = exercises.questID', 'inner')
				->where('exercises.id', $id);
		$query = $this->db->get();
		$name = $query->result()[0]->name;

 		return $name;
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
	public function getIDNext($id) {

		$id_next = NULL;

		$round_max  = $this->getMaxRound($id);
		$round_user = $this->Session->getUserRound($id);

		if ($round_user < $round_max) {

			// User has not solved all rounds of exercise
			$id_next = $id;

 		} else {

 			$id_next = NULL;
		}

 		return $id_next;
	}
}

?>