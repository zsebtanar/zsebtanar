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
	 * Increases user level in case of correct answer.
	 * Decreases user level in case of wrong answer (if possible).
	 *
	 * @param  int 	  $id     Subtopic/Exercise ID
	 * @param  int    $level  Current exercise level
	 * @param  string $result Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function UpdateResults($id, $level, $result) {



		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$level_max 	= $query->result()[0]->level;
		$results 	= $this->session->userdata('levels');

		if ($result == 'CORRECT') {

			$results[$id] = $level;
			$this->session->set_userdata('levels', $results);	

		}

		return;
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

		print_r('Completed quests: ');
		print_r($this->session->userdata('quests'));
		print_r('Exercises: ');
		print_r($this->session->userdata('levels'));

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