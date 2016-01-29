<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->model('Exercises');
		$this->load->model('Database');

		return;
	}

	/**
	 * Unset user data
	 *
	 * Unsets used defined session variables.
	 *
	 * @return void
	 */
	public function UnsetUserData() {

		$user_data = $this->session->all_userdata();

		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' &&
				$key != 'ip_address' &&
				$key != 'user_agent' &&
				$key != 'last_activity' &&
				$key != 'Logged_in') {

				$this->session->unset_userdata($key);
			}
		}

		return;
	}

	/**
	 * Update results
	 *
	 * @param int    $id      Exercise ID
	 * @param string $message Message for user
	 *
	 * @return string $message Message for user (updated)
	 */
	public function UpdateResults($id, $message) {

		$level_user = $this->getUserLevel($id);
		$level_max = $this->Exercises->getMaxLevel($id);

		// Update levels
		$levels = $this->session->userdata('levels');
		$levels[$id] = $level_user + 1;
		$this->session->set_userdata('levels', $levels);
		$this->AddPoint(100);
		$message .= '<br />+100&nbsp;<img src="'.
			base_url().'assets/images/coin.png" alt="coin" width="30">';

		// Check progress
		$progress_old = $level_user/$level_max;
		$progress_new = ($level_user+1)/$level_max;

		if (($progress_old < 1/3 && $progress_new >= 1/3) ||
			($progress_old < 2/3 && $progress_new >= 2/3) ||
			($progress_old < 3/3 && $progress_new >= 3/3)) {

			// Update points
			$prize = 200;
			$this->AddPoint($prize);
			$message .= '<br /><br />Szintet léptél!<br />+'.$prize.
				'&nbsp;<img src="'.base_url().
				'assets/images/coin.png" alt="coin" width="30">';

			// Update quests
			if ($progress_new == 1) {
				$prize = 500;
				$this->AddPoint($prize);
				$message .= '<br /><br />Elvégeztél egy feladatot!<br />+'.$prize.
					'&nbsp;<img src="'.base_url().
					'assets/images/coin.png" alt="coin" width="30">';

				$message = $this->UpdateQuest($id, $message);
				$message = $this->UpdateSubtopic($id, $message);
			}
		}

		return $message;
	}

	/**
	 * Update quest results
	 *
	 * Checks is all exercises of quest has been completed.
	 *
	 * @param int    $id      Exercise ID
	 * @param string $message Message for user
	 *
	 * @return string $message Message for user (updated)
	 */
	public function UpdateQuest($id, $message) {

		$questID 	= $this->Exercises->getQuestID($id);
		$query 		= $this->db->get_where('exercises', array('questID' => $questID));
		$exercises 	= $query->result();
		$total		= count($exercises);

		$iscomplete = TRUE;
		foreach ($exercises as $exercise) {

			$level_user = $this->getUserLevel($exercise->id);
			$level_max = $this->Exercises->getMaxLevel($exercise->id);

			if ($level_user < $level_max) {

				$iscomplete = FALSE;
				break;
			}
		}

		if ($iscomplete) {

			$quests = $this->session->userdata('quests');
			$quests[$questID] = 1;
			$this->session->set_userdata('quests', $quests);

			$prize = $total * 1000;
			$this->AddPoint($prize);
			$message .= '<br /><br />Elvégeztél egy küldetést!<br />'.
				'+1&nbsp;<img src="'.base_url().
				'assets/images/shield.png" alt="coin" width="30">&nbsp;&nbsp;'.
				'+'.$prize.'&nbsp;<img src="'.base_url().
				'assets/images/coin.png" alt="coin" width="30">';
		}

		return $message;
	}

	/**
	 * Update subtopic results
	 *
	 * Checks is all quests of subtopic has been completed.
	 *
	 * @param int    $id      Exercise ID
	 * @param string $message Message for user
	 *
	 * @return string $message Message for user (updated)
	 */
	public function UpdateSubtopic($id, $message) {

		$subtopicID	= $this->Exercises->getSubtopicID($id);
		$query 		= $this->db->get_where('quests', array('subtopicID' => $subtopicID));
		$quests 	= $query->result();
		$total 		= count($quests);

		$result 	= $this->session->userdata('quests');
		$iscomplete = TRUE;

		foreach ($quests as $quest) {

			if (!isset($result[$quest->id]) || $result[$quest->id]!= 1) {
				$iscomplete = FALSE;
				break;
			}
		}

		if ($iscomplete) {

			$subtopics = $this->session->userdata('subtopics');
			$subtopics[$subtopicID] = 1;
			$this->session->set_userdata('subtopics', $subtopics);

			$prize = $total * 5000;
			$this->AddPoint($prize);
			$message .= '<br /><br />Elvégeztél egy témakört!<br />'.
				'+1&nbsp;<img src="'.base_url().
				'assets/images/trophy.png" alt="coin" width="30">&nbsp;&nbsp;'.
				'+'.$prize.'&nbsp;<img src="'.base_url().
				'assets/images/coin.png" alt="coin" width="30">';
		}

		return $message;
	}

	/**
	 * Check whether subtopic is complete
	 *
	 * Checks if all quests have been completed by user
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return bool $iscomplete Whether  $html Html-code
	 */
	public function isSubtopicComplete($id) {

		$subtopics = $this->session->userdata('subtopics');

		$iscomplete = (isset($subtopics[$id]) && $subtopics[$id] == 1);

		return $iscomplete;
	}

	/**
	 * Add points
	 *
	 * @param int $amount Amount of money
	 *
	 * @return void
	 */
	public function AddPoint($amount) {

		$points = $this->session->userdata('points');
		$points += $amount;
		$this->session->set_userdata('points', $points);

		return;
	}

	/**
	 * Get results
	 *
	 * @param string $type View type (exercise/subtopic)
	 * @param int    $id   Exercise/subtopic id
	 *
	 * @return array $data Results
	 */
	public function GetResults($type=NULL, $id=NULL) {

		$points = $this->session->userdata('points');
		$data['points'] = ($points ? $points : 0);

		$quests = $this->session->userdata('quests');
		$data['quests'] = ($quests ? array_sum($quests) : 0);

		$quests = $this->session->userdata('subtopics');
		$data['subtopics'] = ($quests ? array_sum($quests) : 0);

		$data['id'] = $id;
		$data['type'] = $type;

		return $data;
	}

	/**
	 * Get user progress for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $data Exercise progress (value + style) 
	 */
	public function getUserProgress($id) {

		$level_max = $this->Exercises->getMaxLevel($id);
		$level_user = $this->getUserLevel($id);
		
		$progress = $level_user/$level_max;
		
		if ($progress < 1/3) {
			$style = 'info';
		} elseif ($progress < 2/3) {
			$style = 'warning';
		} else {
			$style = 'danger';
		}

		$progress = min(100, round($progress*100));

		$data = ['value' => $progress, 'style' => $style];

		return $data;
	}

	/**
	 * Get user level for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $level_user User level
	 */
	public function getUserLevel($id) {

		$results = $this->session->userdata('levels');
		$level_user = (isset($results[$id]) ? $results[$id] : 0);

 		return $level_user;
	}

	/**
	 * Print session information
	 *
	 * @return void
	 */
	public function PrintInfo() {

		// print_r('Subtopics: ');
		// print_r($this->session->userdata('subtopics'));
		// print_r('Quests: ');
		// print_r($this->session->userdata('quests'));
		// print_r('<br />Levels: ');
		// print_r($this->session->userdata('levels'));
		// print_r('<br />Points: ');
		// print_r($this->session->userdata('points'));
		// print_r('<br />Exercise: ');
		// print_r($this->session->userdata('exercise'));

		return;
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
	public function SaveExerciseData($id, $level, $data, $hash) {

		$sessiondata = $this->session->userdata('exercise');

		$explanation = (isset($data['explanation']) ? $data['explanation'] : NULL);

		$sessiondata[$hash] = array(
			'id'		=> $id,
			'level' 	=> $level,
			'correct' 	=> $data['correct'],
			'type' 		=> $data['type'],
			'solution'	=> $data['solution'],
			'explanation' => $explanation
		);

		$this->session->set_userdata('exercise', $sessiondata);

		return;
	}

	/**
	 * Get exercise data from session
	 *
	 * @param string $hash Random string
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($hash) {

		$exercise = $this->session->userdata('exercise');

		$correct 	= $exercise[$hash]['correct'] ; 
		$solution  	= $exercise[$hash]['solution'];  
		$level 		= $exercise[$hash]['level']; 
		$type 		= $exercise[$hash]['type']; 
		$id 		= $exercise[$hash]['id'];
		$explanation = $exercise[$hash]['explanation'];

		return array($correct, $explanation, $solution, $level, $type, $id);
	}

	/**
	 * Delete exercise data
	 *
	 * Removes exercise data from session if user has answered exercise (either
	 * correct or wrong)
	 *
	 * @param string $status Status (NOT_DONE/CORRECT/WRONG)
	 * @param string $hash   Random string
	 *
	 * @return void
	 */
	public function DeleteExerciseData($status, $hash) {

		if ($status != 'NOT_DONE') {
			$exercise = $this->session->userdata('exercise');
			unset($exercise[$hash]);
			$this->session->set_userdata('exercise', $exercise);
		}

		return;
	}

	/**
	 * Check login
	 *
	 * Checks if user is logged in
	 *
	 * @return bool $status Whether user logged in or not
	 */
	public function CheckLogin() {

		if (NULL !== $this->session->userdata('Logged_in') &&
			$this->session->userdata('Logged_in')) {

			$status = TRUE;

		} else {

			$status = FALSE;

		}

		return $status;
	}
}

?>