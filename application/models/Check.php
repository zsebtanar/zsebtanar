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
			$separator,
			$level, $type, $id) = $this->Session->GetExerciseData($hash);

		list($status, $message, $submessages) = $this->GenerateMessages($type, $answer, $correct, $solution, $separator);

		if ($status == 'CORRECT') {
			$this->Session->DeleteExerciseData($hash);
			$message = $this->Session->UpdateResults($id, $hints_used, $hints_all, $message);
		}

		$results = $this->Session->GetResults();

		list($id_next, $link_next) = $this->Database->NextExercise($id);

		$subtopiclabel 	= $this->Database->getSubtopicLabel($id);
		$progress 		= $this->Session->UserProgress($id);

		$output = array(
			'status' 		=> $status,
			'message' 		=> $message,
			'submessages'	=> $submessages,
			'id_next'		=> $id_next,
			'link_next'		=> $link_next,
			'subtopiclabel'	=> $subtopiclabel,
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
	public function GenerateMessages($type, $answer, $correct, $solution, $separator) {

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

			case 'array':
			case 'range':
			case 'coordinate':
				list($status, $message) = $this->GenerateMessagesArray($answer, $correct, $solution);
				break;

			case 'fraction':
				list($status, $message) = $this->GenerateMessagesFraction($answer, $correct, $solution);
				break;

			case 'list':
			case 'single_list':
			case 'coordinatelist':
				list($status, $message) = $this->GenerateMessagesList($answer, $correct, $solution, $separator);
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
		} elseif (is_integer($correct)) {
			if ($answer[0] == $correct) {
				$status = 'CORRECT';
				$message = 'Helyes válasz!';
			} else {
				$status = 'WRONG';
				$message = 'A helyes válasz: '.$solution;
			}
		} elseif (is_float($correct)) {
			$answer = str_replace(',', '.', $answer[0]);
			while (round($correct) != $correct) {
				$answer *= 10;
				$correct *= 10;
			}
			if (round($answer) == $correct) {
				$status = 'CORRECT';
				$message = 'Helyes válasz!';
			} else {
				$status = 'WRONG';
				$message = 'A helyes válasz: '.$solution;
			}
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
				$submessages[$key] = 'EMPTY';
				if ($value == 1) {
					if (in_array($key, $answer)) {
						$submessages[$key] = 'FILLED_CORRECT';
					} else {
						$submessages[$key] = 'EMPTY_WRONG';
						$message = 'A helyes válasz: '.$solution;
						$status = 'WRONG';
					}
				} else {
					if (in_array($key, $answer)) {
						$submessages[$key] = 'FILLED_WRONG';
						$message = 'A helyes válasz: '.$solution;
						$status = 'WRONG';
					} else {
						$submessages[$key] = 'EMPTY_CORRECT';
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
	 * Generate messages for array numbers
	 *
	 * @param array  $answer   User answer
	 * @param int    $correct  Correct answer
	 * @param string $solution Solution
	 *
	 * @return string $status  Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message Message
	 */
	public function GenerateMessagesArray($answer, $correct, $solution) {

		$isempty = TRUE;
		$iscorrect = TRUE;

		foreach ($answer as $key => $item) {
			$check = $correct[$key];
			$isempty = ($item == NULL ? $isempty : FALSE);
			$item = str_replace(',', '.', $item);
			while (round($check) != $check) { // check precision
				$check 	*= 10;
				$item	*= 10;
			}
			$iscorrect = (round($item) != $check ? FALSE : $iscorrect);
		}

		if ($isempty) {
			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';
		} elseif ($iscorrect) {
			$status = 'CORRECT';
			$message = 'Helyes válasz!';
		} else {
			$status = 'WRONG';
			$message = 'A helyes válasz: '.$solution;
		}

		return array($status, $message);
	}

	/**
	 * Generate messages for List type exercises
	 *
	 * @param array  $answer    User answer
	 * @param int    $correct   Correct answer
	 * @param string $solution  Solution
	 * @param string $separator Separator to create array from answer
	 *
	 * @return string $status     Status (NOT_DONE/CORRECT/WRONG)
	 * @return string $message    Message
	 * @return array  $submessage Submessages
	 */
	public function GenerateMessagesList($answer, $correct, $solution, $separator) {

		if (count($answer) == 1) {
			if ($separator) {
				$answer = array_map('intval', preg_split('/[\s'.$separator.']+/', $answer[0]));
			} else {
				$answer = array_map('intval', preg_split("/[\s,;]+/", $answer[0]));
			}
		}

		$isempty = TRUE;
		$iscorrect = TRUE;

		foreach ($answer as $key => $item) {
			$isempty = ($item == NULL ? $isempty : FALSE);
		}

		if ($isempty) {

			$status = 'NOT_DONE';
			$message = 'Hiányzik a válasz!';

		} else {

			if (is_array($correct[0])) { // correct answer contains subarrays
				
				foreach ($correct as $key => $value) {

					$length = count($value);
					$answertext = '';
					for ($i=0; $i < $length; $i++) { 
						$answertext .= $answer[$key*$length+$i];
					}

					// implode subarrays in correct answer
					$correct2[] = implode('', $value);
					// create array from answer data
					$answer2[] = $answertext;
				}
				
				$answer = $answer2;
				$correct = $correct2;
			}

			$diff = array_intersect($answer, $correct);
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