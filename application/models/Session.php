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
	public function startSession() {

		// $this->session->unset_userdata('sessionID');

		if (NULL === $this->session->userdata('sessionID')) {
			
			if (!$this->db->insert('sessions', '')) {
				show_error($this->db->_error_message());
			}
			$sessionID = $this->db->insert_id();

			$this->session->set_userdata('sessionID', $sessionID);

		}

		if (NULL === $this->session->userdata('Logged_in')) {
			
			$this->session->set_userdata('Logged_in', FALSE);

		}

		if (NULL === $this->session->userdata('Quests')) {
			
			$this->session->set_userdata('Quests', []);

		}

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
	 * Record action
	 *
	 * Data is recorded when user attempts to solve an exercise.
	 *
	 * @param int 	 $id     Subtopic/Exercise ID
	 * @param string $hash   Random string
	 * @param int    $level  Exercise level
	 * @param string $result Result (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function recordAction($id, $hash, $level=NULL, $result=NULL) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0];
		$name 		= $exercise->name;
		$level_max	= $exercise->level;

		$quests = $this->session->userdata('Quests');
		$questID = $quests[$hash]['questID'];

		$data['questID'] 	= $questID;
		$data['level']		= $level;
		$data['level_max']	= $level_max;
		$data['result']		= $result;
		$data['name'] 		= $name;

		$todo_length		= count($this->session->userdata('todo_list'));
		$data['todo'] 		= $todo_length;

		if (!$this->db->insert('actions', $data)) {
			show_error($this->db->_error_message());
		}

		return;
	}

	/**
	 * Update results
	 *
	 * Increases user level in case of correct answer.
	 * Decreases user level at maximum level if answer was wrong (to ensure 
	 * user gets same exercise at top level next time).
	 *
	 * @param int 	 $id     Subtopic/Exercise ID
	 * @param string $hash   Random string
	 * @param int    $level  Current exercise level
	 * @param string $result Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function UpdateResults($id, $hash, $level=NULL, $result=NULL) {

		$level_max 	= $this->Exercises->getMaxLevel($id);

		$quests = $this->session->userdata('Quests');
		$results = $quests[$hash]['results'];

		if ($result == 'CORRECT') {

			$results[$id] = $level;
			$this->session->set_userdata('results', $results);	

		} elseif ($result == 'WRONG' && 
			isset($results[$id]) &&
			$results[$id] == $level_max) {

			$results[$id] = $level-1;
			$this->session->set_userdata('results', $results);
		}

		$quests[$hash]['results'] = $results;
		$this->session->set_userdata('Quests', $quests);

		return;
	}

	/**
	 * Get user levels for current exercise
	 *
	 * Returns an array with 0s and 1s. 1 means the user has answered the exercise
	 * at the specific level correctly, O means the opposite. Data is used to update 
	 * star icons with javascript.
	 *
	 * @param int 	 $id     Exercise ID
	 * @param string $hash   Random string
	 *
	 * @return array $levels Exercise levels (0 or 1)
	 */
	public function getUserLevels($id, $hash) {

		$max_level = $this->Exercises->getMaxLevel($id);

		$quests = $this->session->userdata('Quests');
		$results = $quests[$hash]['results'];

		if (isset($results[$id])) {
			$user_level = $results[$id];
		} else {
			$user_level = 0;
		}

		for ($i=1; $i <= $max_level; $i++) { 
			if ($i <= $user_level) {
				$levels[$i] = 1;
			} else {
				$levels[$i] = 0;
			}
		}

		return $levels;
	}

	/**
	 * Clear exercise results of subtopics from session
	 *
	 * @param  int 	$subtopicID Subtopic ID
	 * @return void
	 */
	public function clearResults($subtopicID) {

		$results = $this->session->userdata('results');

		$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
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
	 * @param int    $id   Exercise ID
	 * @param string $hash Random string
	 *
	 * @return int $level Exercise level 
	 */
	public function getExerciseLevelNext($id, $hash) {

		$level_max = $this->Exercises->getMaxLevel($id);

		$quests = $this->session->userdata('Quests');
		$results = $quests[$hash]['results'];

		if (isset($results[$id])) {

			// User has already solved exercise
			$level_user = $results[$id];
			$level = min($level_max, $level_user+1);

		} else {

			// New exercise
			$level = 1;
		}

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

		print_r('Session ID: '.$this->session->userdata('sessionID').'<br />');

		$quests = $this->session->userdata('Quests');

		foreach ($quests as $hash => $quest) {

			if ($hash) {
				print_r('<hr />Hash: '.$hash.'<br />');
				print_r('Quest ID: '.$quest['questID'].'<br />');
				print_r('Method: '.$quest['method'].' - ');
				print_r($quest['goal'].'<br />'.'To do list: ');
				print_r($quest['todo_list']);
				print_r('<br />Results: ');
				print_r($quest['results']);
				print_r('<br />Exercise data: ');
				print_r($quest['exercise']);
			}
		}

		return;
	}

	/**
	 * Update to do list
	 *
	 * 1. Removes all exercises from to do list after current one (if any).
	 * 2. Adds exercise if not in to do list yet.
	 * 3. Removes exercise if user has completed it in all level.
	 *
	 * @param int    $id   Exercise ID
	 * @param string $hash Random string
	 *
	 * @return void
	 */
	public function UpdateTodoList($id, $hash) {

		$level_max  = $this->Exercises->getMaxLevel($id);
		$level_user = $this->Exercises->getUserLevel($id, $hash);

		$quests = $this->session->userdata('Quests');
		$todo_list = $quests[$hash]['todo_list'];

		// Does exercise occur in todo list?
		if (in_array($id, $todo_list)) {

			$index = array_search($id, $todo_list);

			// Has user solved exercise at max level?
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

		// Save updated to do list
		$quests[$hash]['todo_list'] = $todo_list;
		$this->session->set_userdata('Quests', $quests);
		
		return;
	}

	/**
	 * Record quest into database
	 *
	 * Data is recorded into database when user starts quest.
	 *
	 * @param  string $hash Random string
	 * @return void
	 */
	public function RecordQuestStart($hash) {

		// Get current method and goal
		$quests = $this->session->userdata('Quests');

		$method = $quests[$hash]['method'];
		$id 	= $quests[$hash]['goal'];

		// Insert action into database
		if ($method == 'exercise') {

			$query = $this->db->get_where('exercises', array('id' => $id));
			$class = $this->Database->GetClassName($id, 'exercise');

		} elseif ($method == 'subtopic') {

			$query = $this->db->get_where('subtopics', array('id' => $id));
			$class = $this->Database->GetClassName($id, 'subtopic');
			
		}

		$data['name'] 		= $query->result()[0]->name;
		$data['sessionID'] 	= $this->session->userdata('sessionID');
		$data['method'] 	= $method;
		$data['class'] 		= $class;
		$data['status'] 	= 'STARTED';

		if (!$this->db->insert('quests', $data)) {
			show_error($this->db->_error_message());
		}

		// Save quest ID into session
		$questID = $this->db->insert_id();
		$quests[$hash]['questID'] = $questID;
		$this->session->set_userdata('Quests', $quests);

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

		$this->session->unset_userdata('method');
		$this->session->unset_userdata('goal');
		$this->session->unset_userdata('todo_list');

		return;
	}

	/**
	 * Complete quest
	 *
	 * Data is updated when user starts quest (i.e. sets learning goal),
	 * or completes it.
	 *
	 * @return void
	 */
	public function CompleteQuest() {

		$questID = $this->session->userdata('questID');

		$query = $this->db->get_where('quests', array('id' => $questID));
		$id = $query->result()[0]->id;

		$data['status'] = 'COMPLETED';

		$this->db->where('id', $id);
		$this->db->update('quests', $data); 

		$this->session->unset_userdata('questID');
		$this->session->unset_userdata('method');
		$this->session->unset_userdata('goal');

		return;
	}

	/**
	 * Get exercise data from session
	 *
	 * @param  string $hash Random string
	 * @return array  $data Exercise data
	 */
	public function GetExerciseData($hash) {

		$exercise = $this->session->userdata('exercise');

		$data['correct'] 	= $exercise[$hash]['correct'] ; 
		$data['solution']  	= $exercise[$hash]['solution'];  
		$data['level'] 		= $exercise[$hash]['level']; 
		$data['type'] 		= $exercise[$hash]['type']; 
		$data['id'] 		= $exercise[$hash]['id'];

		unset($exercise[$hash]);

		$this->session->set_userdata('exercise', $exercise);

		return $data;
	}
}

?>