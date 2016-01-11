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

		$message = $this->UpdateRound($id, $message);
		$message = $this->UpdateLevel($id, $message);

		return $message;
	}

	/**
	 * Update round for exercise
	 *
	 * @param int    $id      Exercise ID
	 * @param string $message Message for user
	 *
	 * @return string $message Message for user (updated)
	 */
	public function UpdateRound($id, $message) {

		$round_user = $this->getUserRound($id);
		$round_max = $this->Exercises->getMaxRound($id);

		$rounds = $this->session->userdata('rounds');

		$rounds[$id] = ++$round_user;
		$this->session->set_userdata('rounds', $rounds);

		$prize = ($round_user <= $round_max ? 100 : 10);

		$this->AddPoint($prize);

		$message .= '<br />+'.$prize.'&nbsp;<img src="'.
			base_url().'assets/images/coin.png" alt="coin" width="30">';

		return $message;
	}

	/**
	 * Update quest of exercise
	 *
	 * @param int    $id      Exercise ID
	 * @param string $message Message for user
	 *
	 * @return string $message Message for user (updated)
	 */
	public function UpdateQuest($id, $message) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0];
		$questID 	= $exercise->questID;

		$query 		= $this->db->get_where('exercises', array('questID' => $questID));
		$exercises 	= $query->result();

		$iscomplete = TRUE;
		foreach ($exercises as $exercise) {

			$level_user = $this->getUserLevel($exercise->id);
			$level_max = $this->Exercises->getMaxLevel($exercise->id);

			if ($level_user != $level_max) {

				$iscomplete = FALSE;
				break;
			}
		}

		if ($iscomplete) {
			$quests = $this->session->userdata('quests');
			$quests[$questID] = 1;
			$this->session->set_userdata('quests', $quests);

			$message .= '<br />Elvégeztél egy küldetést!<br />+1'.
				'&nbsp;<img src="'.base_url().
				'assets/images/shield1.png" alt="coin" width="30">+2000'.
				'&nbsp;<img src="'.base_url().
				'assets/images/coin.png" alt="coin" width="30">';

			$this->AddPoint(2000);
		}

		return $message;
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
	 * @return array $data Results
	 */
	public function GetResults() {

		$points = $this->session->userdata('points');
		$data['points'] = ($points ? $points : 0);

		$quests = $this->session->userdata('quests');
		$data['quests'] = ($quests ? array_sum($quests) : 0);

		return $data;
	}

	/**
	 * Update level for exercise
	 *
	 * @param int    $id      Exercise ID
	 * @param string $message Message for user
	 *
	 * @return string $message Message for user (updated)
	 */
	public function UpdateLevel($id, $message) {

		$round_user = $this->getUserRound($id);
		$level_user = $this->getUserLevel($id);

		$round_max = $this->Exercises->getMaxRound($id);
		$level_max = $this->Exercises->getMaxLevel($id);

		$progress_round = $round_user/$round_max;
		$progress_level = ($level_user+1)/$level_max;

		if ($progress_round >= $progress_level && $level_user < $level_max) {

			// Update user level
			$levels = $this->session->userdata('levels');
			$levels[$id] = ++$level_user;
			$this->session->set_userdata('levels', $levels);

			// Update quests
			if ($level_user == $level_max) {
				$message = $this->UpdateQuest($id, $message);
			}

			// Update points
			$points = array(1 => 500, 2 => 1000, 3 => 2000);
			$this->AddPoint($points[$level_user]);
			$message .= '<br />Szintet léptél!<br />+'.$points[$level_user].
				'&nbsp;<img src="'.base_url().
				'assets/images/coin.png" alt="coin" width="30">';

		}

		return $message;
	}

	/**
	 * Get next level of exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $level Exercise level 
	 */
	public function getExerciseLevelNext($id) {

		$level_max = $this->Exercises->getMaxLevel($id);
		$level_user = $this->getUserLevel($id);

		$level = min($level_max, $level_user+1);

		return $level;
	}

	/**
	 * Get user progress for exercise
	 *
	 * $progress shows how many percent of rounds were solved by user.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $progress Exercise progress (%) 
	 */
	public function getUserProgress($id) {

		$round_max = $this->Exercises->getMaxRound($id);
		$round_user = $this->getUserRound($id);
		
		$progress = round($round_user/$round_max*100);

		return $progress;
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
	 * Get user round for exercise
	 *
	 * $round_user shows how many times user has solved the exercise.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $round_user User level
	 */
	public function getUserRound($id) {

		$rounds = $this->session->userdata('rounds');
		$round_user = (isset($rounds[$id]) ? $rounds[$id] : 0);

 		return $round_user;
	}

	/**
	 * Print session information
	 *
	 * @return void
	 */
	public function PrintInfo() {

		// print_r('Quests: ');
		// print_r($this->session->userdata('quests'));
		// print_r('<br />Levels: ');
		// print_r($this->session->userdata('levels'));
		// print_r('<br />Rounds: ');
		// print_r($this->session->userdata('rounds'));
		// print_r('<br />Points: ');
		// print_r($this->session->userdata('points'));

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

		return array($correct, $solution, $level, $type, $id);
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
}

?>