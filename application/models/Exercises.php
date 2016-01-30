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
		$progress 	= $this->Session->getUserProgress($id);

		$output = array(
			'status' 		=> $status,
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
	 * @param int $id    Exercise ID
	 * @param int $level Exercise level
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($id, $level) {

		$this->load->helper('string');

		// Get exercise level
		if (!$level) {
			$level_current = $this->Session->getUserLevel($id);
			$level_new = ++$level_current;
		}

		// Generate exercise
		$this->load->helper('maths');
		$this->load->helper('language');
		$exercise = $this->LoadExerciseFunction($id);
		$function = $exercise->label;
		$data = $function($level_current);

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

		$this->Session->SaveExerciseData($id, $level, $data, $hash);

		$data['level'] 		= $level;
		$data['youtube'] 	= $exercise->youtube;
		$data['hint'] 		= $exercise->hint;
		$data['questID']	= $exercise->questID;
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
				$explanation = '<ul>';
				foreach ($data['explanation'] as $segment) {
					if (is_array($segment)) {
						$explanation .= '<ul>';
						foreach ($segment as $subsegment) {
							if (is_array($subsegment)) {
								$explanation .= '<ul>';
								foreach ($subsegment as $subsubsegment) {
									if (is_array($subsubsegment)) {
										print_r($subsubsegment);
										break;
									}
									$explanation .= '<li>'.strval($subsubsegment).'</li>';
								}
								$explanation .= '</ul>';
							} else {
								$explanation .= '<li>'.$subsegment.'</li>';
							}
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

		} else {

			$data['explanation'] = 'Sajnos ehhez a feladathoz még nincs megoldókulcs. Szeretnéd, ha lenne? Írj egy emailt a <b>zsebtanar@gmail.com</b>-ra!';			
		}

		return $data;
	}

	/**
	 * Get quests of subtopic
	 *
	 * @param int $subtopicID Subtopic ID
	 * @param int $questID    Quest ID
	 *
	 * @return array $data Quests
	 */
	public function getSubtopicQuests($subtopicID=NULL, $questID=NULL) {

		$query = $this->db->get_where('quests', array('subtopicID' => $subtopicID));
		$quests = $query->result();
		$data = NULL;

		if (count($quests) > 0) {
			foreach ($quests as $quest) {

				$row['id'] 			= $quest->id;
				$row['name'] 		= $quest->name;
				$row['exercises'] 	= $this->getQuestExercises($quest->id);
				$row['links'] 		= $this->getQuestLinks($quest->id);
				$row['complete'] 	= $this->isComplete($quest->id);

				if ($this->Session->CheckLogin() || $quest->id == $questID) {
					$row['class'] = 'in';
				} else {
					$row['class'] = '';
				}

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

				$id = $exercise->id;

				$row['userlevel'] 	= $this->Session->getUserLevel($id);
				$row['id'] 			= $id;
				$row['name'] 		= $exercise->name;
				$row['status'] 		= $exercise->status;

				$data[] = $row;
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

		$level_max  = $this->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		if ($level_user < $level_max) {

			// User has not solved all rounds of exercise
			$id_next = $id;

 		} else {

 			$id_next = NULL;
		}

 		return $id_next;
	}
}

?>