<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		$this->load->helper('url');
		$this->load->helper('file');

		return;
	}

	/**
	 * Set session ID
	 *
	 * @return void
	 */
	public function setSessionID() {

		// $this->session->unset_userdata('sessionID');

		if (NULL === $this->session->userdata('sessionID')) {
			
			// Define session ID
			$this->db->select_max('sessionID');
			$query = $this->db->get('actions');
			$sessionID = $query->result_array()[0]['sessionID'];

			if (NULL == $sessionID) {
				$this->session->set_userdata('sessionID', 1);
			} else {
				$this->session->set_userdata('sessionID', $sessionID+1);
			}

			// Define session results
			if (NULL == $this->session->userdata('results')) {
				$this->session->set_userdata('results', []);	
			}

		}

		return;
	}

	/**
	 * Record action
	 *
	 * @param  int 	  $id     Subtopic/Exercise ID
	 * @param  string $type   View type (exercise/subtopic)
	 * @param  int    $level  Exercise level
	 * @param  string $result Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function recordAction($id, $type, $level=NULL, $result=NULL) {

		if ($type == 'subtopic') {

			if (!$id) {
				$name 		= 'Kezdőlap';
			} else {
				print_r($id);
				$query 		= $this->db->get_where('subtopics', array('id' => $id));
				$subtopic 	= $query->result()[0];
				$name 		= $subtopic->name;
			}

			$this->insertSubtopicAction($name, $type);

		} elseif ($type == 'exercise') {

			$query 		= $this->db->get_where('exercises', array('id' => $id));
			$exercise 	= $query->result()[0];
			$name 		= $exercise->name;
			$level_max	= $exercise->level;

			$this->insertExerciseAction($name, $level, $level_max, $result);

			if ($result) {
				$this->updateResults($id, $level, $level_max, $result);
			}

			$this->saveExerciseID($id);
		}

		return;
	}

	/**
	 * Insert subtopic action into database
	 *
	 * @param  string $name   Subtopic name
	 * @param  int    $level  Exercise level
	 * @param  string $result Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function insertSubtopicAction($name) {

		$data['sessionID'] 	= $this->session->userdata('sessionID');
		$data['type']		= 'subtopic';
		$data['name']		= $name;

		if (!$this->db->insert('actions', $data)) {
			show_error($this->db->_error_message());
		}

		return;
	}

	/**
	 * Insert action into database
	 *
	 * @param  string $name      Exercise name
	 * @param  int    $level     Current level
	 * @param  int    $level_max Exercise level
	 * @param  string $result    Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function insertExerciseAction($name, $level=NULL, $level_max, $result=NULL) {

		$data['sessionID'] 	= $this->session->userdata('sessionID');
		$data['type']		= 'exercise';
		$data['level']		= $level.'/'.$level_max;
		$data['result']		= $result;
		$data['name'] 		= $name;

		if (!$this->db->insert('actions', $data)) {
			show_error($this->db->_error_message());
		}

		return;
	}

	/**
	 * Update results
	 *
	 * @param  int 	  $id        Subtopic/Exercise ID
	 * @param  int    $level     Current exercise level
	 * @param  int    $level_max Exercise level
	 * @param  string $result    Result of action (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function updateResults($id, $level=NULL, $level_max, $result=NULL) {

		$results = $this->session->userdata('results');

		if ($result == 'CORRECT') {

			$results[$id] = $level;
			$this->session->set_userdata('results', $results);	

		} elseif ($result == 'WRONG') {

			if (isset($results[$id])) {

				$level_old = $results[$id];
				$level_new = max(0, min($level-1,$level_old-1));
				$results[$id] = $level_new;
				$this->session->set_userdata('results', $results);

			}
		}

		return;
	}

	/**
	 * Get actions of session
	 *
	 * @param  int 	 $id   Session ID
	 * @return array $data Session data
	 */
	public function getActions($id=NULL) {

		$this->db->select('sessionID');
		$this->db->distinct();

		$query = $this->db->get('actions');

		foreach ($query->result() as $session) {
			$sessionIDs[] = $session->sessionID; 
		}

		$data['all_sessions'] = $sessionIDs;
		$data['current_id'] = $id;

		if ($id) {
			$query = $this->db->get_where('actions', array('sessionID' => $id));
			foreach ($query->result_array() as $session) {
				$data['current_session'][] = $session; 
			}
		}

		return $data;
	}

	/**
	 * Get saved sessions
	 *
	 * @param  int 	 $id   Session ID
	 * @return array $data Session data
	 */
	public function getSavedSessions($file) {

		if (!$file) {

			$files = get_filenames('./resources/saved_sessions');
			
			foreach ($files as $file) {

				$data['files'][] = $file;

			}
		} else {

			$this->load->library('csvreader');
			$file_path = base_url().'./resources/saved_sessions/'.$file;

			$data = $this->Session->getActions();
			$content = $this->csvreader->parse_file($file_path);
			$data['from_file'] = TRUE;
			$data['current_session'] = $content;

		}

		return $data;
	}

	/**
	 * Get user results of exercise
	 *
	 * Returns an array with 0s and 1s. 1 means the user has answered the exercise
	 * at the specific level correctly, O means the opposite. 
	 *
	 * @param  int 	 $id     Exercise ID
	 * @return array $levels Exercise levels (0 or 1)
	 */
	public function getExerciseResults($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise = $query->result()[0];
		$max_level = $exercise->level;

		if (NULL !== $this->session->userdata('results')[$id]) {
			$user_level = $this->session->userdata('results')[$id];
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

		$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
		foreach ($query->result() as $exercise) {

			$id = $exercise->id;
			if (NULL !== $this->session->userdata('results')[$id]) {
				$this->session->unset_userdata('results')[$id];
			}
		}

		return;
	}

	/**
	 * Get next level of exercise
	 *
	 * @param  int $id    Exercise ID
	 * @return int $level Exercise level 
	 */
	public function getExerciseLevelNext($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise = $query->result()[0];
		$level_max = $exercise->level;

		if (isset($this->session->userdata('results')[$id])) {

			$level_user = $this->session->userdata('results')[$id];
			$level = min($level_max, $level_user+1);

		} else {

			$level = 1;
		}

		return $level;
	}

	/**
	 * Save exercise ID
	 *
	 * Saves exercise ID to list. 
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
}

?>