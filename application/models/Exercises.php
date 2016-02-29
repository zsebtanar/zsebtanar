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
			$results = $this->Session->GetResults();
		}

		$id_next 	= $this->getIDNext($id);
		$subtopicID = $this->getSubtopicID($id);
		$progress 	= $this->Session->getUserProgress($id);

		$output = array(
			'status' 		=> $status,
			'message' 		=> $message,
			'submessages'	=> $submessages,
			'id_next'		=> $id_next,
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
			$level_max = $this->getMaxLevel($id);

			$level = min($level_max, ++$level_user);
		}

		// Generate exercise
		$this->load->helper('maths');
		$this->load->helper('language');
		$exercise = $this->LoadExerciseFunction($id);
		$function = $exercise->label;
		$data = $function($level);

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
		$data['youtube'] 	= $exercise->youtube;
		$data['hint'] 		= $exercise->hint;
		$data['id'] 		= $id;
		$data['hash']		= $hash;
		$data['subtopicID'] = $this->getSubtopicID($id);

		return $data;
	}

	/**
	 * Load exercise
	 *
	 * Loads specific helper to access exercise function
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $exercise Exercise data
	 */
	public function LoadExerciseFunction($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 

		// Load exercise function
		$function = $exercise->label;

		$this->load->model('Database');
		$class = $this->Database->getClassLabel($id);
		$topic = $this->Database->getTopicLabel($id);
		$subtopic = $this->Database->getSubtopicLabel($id);

		if (!function_exists($function)) {
			$this->load->helper('exercises/'.$class.'/'.$topic.'/'.$subtopic.'/functions');
		}

		return $exercise;
	}

	/**
	 * Get link for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $href Link
	 */
	public function getExerciseLink($id) {

		$exercises = $this->db->get_where('exercises', array('id' => $id));

		if (count($exercises->result()) == 1) {

			$exercise = $exercises->result()[0];

			$title = $exercise->name;

			$href = base_url().'view/exercise/'.$exercise->id;

		} else {

			$href = base_url().'view/main/';

		}

		return $href;
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
								$explanation = $subsegment[0].'<button class="pull-right btn btn-default" data-toggle="collapse" data-target="#hint_details">Részletek</button><br/>';
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
		} elseif ($this->hasHint($id)) {
			$data['explanation'][0] = 'Segítségre van szükséged? Kattints a <img src="'.base_url().'assets/images/light_bulb.png" alt="hint" width="40">-ra!';
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
	 * Get exercises of subtopic
	 *
	 * @param int $subtopicID Subtopic ID
	 * @param int $exerciseID Exercise ID
	 *
	 * @return array $data Exercises
	 */
	public function getSubtopicExercises($subtopicID=NULL, $exerciseID=NULL) {

		$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
		$exercises = $query->result();
		$data = NULL;

		if (count($exercises) > 0) {
			foreach ($exercises as $exercise) {

				$id = $exercise->id;
				$exercisedata = $this->GetExerciseData($id, NULL, $save=FALSE);

				$row['id'] 			= $id;
				$row['name'] 		= $exercise->name;
				$row['complete'] 	= $this->isComplete($id);
				$row['progress'] 	= $this->Session->getUserProgress($id);
				$row['status'] 		= $exercise->status;
				$row['hint'] 		= $exercise->hint;
				$row['youtube']		= $exercise->youtube;
				$row['question']	= $exercisedata['question'];
				$row['explanation']	= $exercisedata['explanation'];
				$row['class'] 		= (!$exerciseID || $id == $exerciseID ? 'in' : '');

				$data[] = $row;
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

	/**
	 * Get hint for exercise
	 *
	 * @param string $hash Exercise hash
	 * @param int    $id   Order of hint
	 *
	 * @return string $explanation
	 */
	public function GetHint($hash, $id) {

		$this->load->model('Session');

		$data = $this->Session->GetExerciseHint($hash, $id);

		return $data;
	}

	/**
	 * Check if exercise is completed
	 *
	 * @param int $id Exercise ID
	 *
	 * @return bool $isComplete Is exercise completed?
	 */
	public function isComplete($exerciseID) {

		$exercises = $this->session->userdata('exercises');

		$isComplete	= (isset($exercises[$exerciseID]) ? $exercises[$exerciseID] : FALSE);

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
	 * $max_level shows how many times user needs to solve the exercise to complete it.
	 * If user is logged in, it is only 3 (for debugging purposes). 
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $max_level Maximum level
	 */
	public function getMaxLevel($id) {

		$query 	= $this->db->get_where('exercises', array('id' => $id));
		$max_level = $query->result()[0]->level;

 		return $max_level;
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
	 * Get subtopic ID for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $subtopicID Subtopic ID
	 */
	public function getSubtopicID($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
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
				->join('exercises', 'subtopics.id = exercises.subtopicID', 'inner')
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

		$level_max  = $this->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		$id_next = ($level_user < $level_max ? $id : $id+1);

 		return $id_next;
	}

	/**
	 * Check whether exercise exists
	 *
	 * @param int $id Exercise ID
	 *
	 * @return bool $exists Whether exercise exists
	 */
	public function ExerciseExists($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));

		$exists = count($query->result()) == 1;

 		return $exists;
	}
}

?>