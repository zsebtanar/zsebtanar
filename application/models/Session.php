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
	 * Start session
	 *
	 * Define session ID and initialize session variables.
	 *
	 * @return void
	 */
	// public function startSession() {

	// 	// $this->session->unset_userdata('sessionID');

	// 	if (NULL === $this->session->userdata('sessionID')) {
			
	// 		if (!$this->db->insert('sessions', '')) {
	// 			show_error($this->db->_error_message());
	// 		}
	// 		$sessionID = $this->db->insert_id();

	// 		$this->session->set_userdata('sessionID', $sessionID);

	// 	}

	// 	if (NULL === $this->session->userdata('Logged_in')) {
			
	// 		$this->session->set_userdata('Logged_in', FALSE);

	// 	}

	// 	return;
	// }

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
	 * @param  int 	  $id        Subtopic/Exercise ID
	 * @param  int    $level     Current exercise level
	 * @param  string $result    Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function UpdateResults($id, $level=NULL, $result=NULL) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$level_max 	= $query->result()[0]->level;
		$results 	= $this->session->userdata('results');

		if ($result == 'CORRECT') {

			$results[$id] = $level;
			$this->session->set_userdata('results', $results);	

		} elseif ($result == 'WRONG' && 
			isset($results[$id]) &&
			$results[$id] == $level_max) {

			$results[$id] = $level-1;
			$this->session->set_userdata('results', $results);
		}

		return;
	}

	/**
	 * Clear exercise results of quest from session
	 *
	 * @param  int 	$questID Quest ID
	 *
	 * @return void
	 */
	public function clearResults($questID) {

		$results = $this->session->userdata('results');

		$query = $this->db->get_where('exercises', array('questID' => $questID));

		foreach ($query->result() as $exercise) {

			$id = $exercise->id;
			if (isset($results[$id])) {
				unset($results[$id]);
			}
		}

		$this->session->set_userdata('results', $results);

		return;
	}

	/**
	 * Get next level of exercise
	 *
	 * @param  int $id    Exercise ID
	 * @return int $level Exercise level 
	 */
	public function getExerciseLevelNext($id) {

		$level_max = $this->Exercises->getMaxLevel($id);
		$level_user = $this->Exercises->getUserLevel($id);
		
		$level = min($level_max, $level_user+1);

		return $level;
	}

	/**
	 * Save exercise ID
	 *
	 * Saves exercise ID to to do list. 
	 * Next exercise will be chosen according to the to the saved list.
	 *
	 * @param  int $id Exercise ID
	 * @return void
	 */
	public function saveExerciseID($id) {

		if (NULL !== $this->session->userdata('method')) {

			$method = $this->session->userdata('method');

			if ($method == 'exercise') {

				$todo_list = $this->session->userdata('todo_list');

				if (!in_array($id, $todo_list)) {
					array_push($todo_list, $id);
				}

				$this->session->set_userdata('todo_list', $todo_list);

			}
		}
		
		return;
	}

	/**
	 * Print session information
	 *
	 * @return void
	 */
	public function PrintInfo() {

		// print_r('Session ID: '.$this->session->userdata('sessionID').'<br />');
		// print_r('Quest ID: '.$this->session->userdata('questID').'<br />');
		print_r('Method: '.$this->session->userdata('method').' - ');
		print_r($this->session->userdata('goal'));
		// print_r('<br />'.'To do list: ');
		// print_r($this->session->userdata('todo_list'));
		print_r('<br />Results: ');
		print_r($this->session->userdata('results'));
		// print_r('<br />Exercise data: ');
		// print_r($this->session->userdata('exercise'));

		return;
	}

	/**
	 * Update to do list
	 *
	 * 1. Removes all exercises after current one (if any).
	 * 2. Adds exercise if not in to do list yet.
	 * 3. Removes exercise if user has completed it in all level.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return void
	 */
	public function UpdateTodoList($id) {

		$level_max  = $this->Exercises->getMaxLevel($id);
		$level_user = $this->Exercises->getUserLevel($id);

		$todo_list = $this->session->userdata('todo_list');
		$results = $this->session->userdata('results');

		if (in_array($id, $todo_list)) {

			$index = array_search($id, $todo_list);

			if ($level_user == $level_max) {
				// Removes current & following exercises
				$todo_list = array_slice($todo_list, 0, $index);
			} else {
				// Removes following exercises
				$todo_list = array_slice($todo_list, 0, $index+1);
			}

		} else {

			if ($level_user < $level_max) {
				// Appends exercise to end of to do list
				$todo_list[] = $id;
			}
		}

		$todo_list = $this->session->set_userdata('todo_list', $todo_list);
		
		return;
	}

	/**
	 * Clear session
	 *
	 * Unsets session variables when quest is finished.
	 *
	 * @return void
	 */
	public function ClearSession() {

		// $this->session->unset_userdata('method');
		// $this->session->unset_userdata('goal');
		$this->session->set_userdata('todo_list', []);

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
	 * Update exercise data
	 *
	 * Remove exercise data from session if user has answered exercise (either
	 * correct or wrong)
	 *
	 * @param string $status Status (NOT_DONE/CORRECT/WRONG)
	 * @param string $hash   Random string
	 *
	 * @return void
	 */
	public function UpdateExerciseData($status, $hash) {

		if ($status != 'NOT_DONE') {
			$exercise = $this->session->userdata('exercise');
			unset($exercise[$hash]);
			$this->session->set_userdata('exercise', $exercise);
		}

		return;
	}
}

?>