<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		parent::__construct();

		$this->load->helper('url');

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
		$this->load->model('Html');

		$answerdata = json_decode($jsondata, TRUE);
		list($answer, $hash) = $this->GetAnswerData($answerdata);
		list($correct,
			$explanation,
			$hints_used,
			$hints_all,
			$solution,
			$level, $type, $id) = $this->Session->GetExerciseData($hash);

		list($status, $message, $submessages) = $this->GenerateMessages($type, $answer, $correct, $solution);

		$this->Session->DeleteExerciseData($status, $hash);

		if ($status == 'CORRECT') {
			$message = $this->Session->UpdateResults($id, $hints_used, $hints_all, $message);
		}

		$results = $this->Session->GetResults();

		$id_next 	= $this->Html->NextID($id);
		$label_next = $this->Html->NextLabel($id);
		$subtopicID = $this->Database->getSubtopicID($id);
		$progress 	= $this->Session->UserProgress($id);

		$output = array(
			'status' 		=> $status,
			'message' 		=> $message,
			'submessages'	=> $submessages,
			'id_next'		=> $id_next,
			'label_next'	=> $label_next,
			'subtopicID'	=> $subtopicID,
			'explanation'	=> $explanation,
			'progress'		=> $progress,
			'results' 		=> $results
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

			case 'fraction':
				list($status, $message) = $this->GenerateMessagesFraction($answer, $correct, $solution);
				break;

			case 'equation2':
				list($status, $message) = $this->GenerateMessagesEquation2($answer, $correct, $solution);
				break;

			case 'range':
				list($status, $message) = $this->GenerateMessagesRange($answer, $correct, $solution);
				break;

			case 'list':
				list($status, $message) = $this->GenerateMessagesList($answer, $correct, $solution);
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
		} elseif (is_numeric($answer[0]) && $answer[0] == $correct) {
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

			list($quotient, $remain) = $correct;

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
	 * Generate messages for fraction type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesFraction($answer, $correct, $solution) {

		if ($answer[0] == NULL && $answer[1] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif ($answer[0] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a számláló!';
		} elseif ($answer[1] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a nevező!';
		} elseif ($answer[1] == 0) {
			$status = 'NOT_DONE';
			$message = 'A nevező nem lehet $0$!';
		} else {

			list($num, $denom) = $correct;
			$frac = $num/$denom;

			$num_user = $answer[0];
			$denom_user = $answer[1];
			$frac_user = $num_user/$denom_user;

			if ($frac != $frac_user) {
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
	 * Generate messages for second order equation type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesEquation2($answer, $correct, $solution) {

		if ($answer[0] == NULL && $answer[1] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif (($answer[0] == NULL || $answer[1] == NULL) && !is_array($correct)) {
			$status = 'WRONG';
			$message = 'A helyes válasz: '.$solution;
		} else {
			$answer[0] = floatval($answer[0]);
			$answer[1] = floatval($answer[1]);
			$result = array_diff($answer, $correct);
			if (count($result) > 0) {
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
	 * Generate messages for List type exercises
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesList($answer, $correct, $solution) {
		if ($answer[0] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} else {
			$list = array_map('intval', preg_split("/[\s,;]+/", $answer[0]));
			$diff = array_intersect($list, $correct);
			if (count($diff) < count($correct)) {
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
	 * Generate messages for Range type exercises - e.g. [-1;2]
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesRange($answer, $correct, $solution) {
		if ($answer[0] == NULL || $answer[1] == NULL) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif ($answer[0] == $correct[0] && $answer[1] == $correct[1]) {
			$status = 'CORRECT';
			$message = 'Helyes válasz!';
		} else {
			$status = 'WRONG';
			$message = 'A helyes válasz: '.$solution;
		}

		return array($status, $message);
	}
}

?>