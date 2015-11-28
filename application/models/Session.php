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

		if (NULL === $this->session->userdata('sessionID')) {
			
			$this->db->select_max('sessionID');
			$query = $this->db->get('actions');
			$sessionID = $query->result_array()[0]['sessionID']; 

			if (NULL == $sessionID) {
				$this->session->set_userdata('sessionID', 1);
			} else {
				$this->session->set_userdata('sessionID', $sessionID+1);
			}

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
	 * @param  string $type   View type (exercise/page)
	 * @param  int    $level  Exercise level
	 * @param  string $result Result of action (correct/wrong/not_done)
	 * @return void
	 */
	public function recordAction($id, $type, $level=NULL, $result=NULL) {

		$data['sessionID'] 	= $this->session->userdata('sessionID');
		$data['type']		= $type;
		$data['level']		= $level;
		$data['result']		= $result;

		if ($type == 'page') {

			if (!$id) {

				$data['name'] 	= 'Kezdőlap';

			} else {

				$subtopics 		= $this->db->get_where('subtopics', array('id' => $id));
				$subtopic 		= $subtopics->result()[0];
				$data['name'] 	= $subtopic->name;

			}

		} elseif ($type == 'exercise') {

			$exercises 		= $this->db->get_where('exercises', array('id' => $id));
			$exercise 		= $exercises->result()[0];
			$data['name'] 	= $exercise->name;
			$data['level']	= $level.'/'.$exercise->level;

		}

		if (!$this->db->insert('actions', $data)) {
			show_error($this->db->_error_message());
		}

		$results = $this->session->userdata('results');
		if ($result == 'CORRECT') {

			$results[$id] = $level;
			$this->session->set_userdata('results', $results);

			if ($level == $exercise->level &&
				NULL !== $this->session->userdata('method')	&&
				$this->session->userdata('method') == 'exercise') {
				$this->updateSession($id, NULL, $action='delete');
			}

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
	 * Get actions
	 *
	 * @param  int 	 $id   Session ID
	 * @return array $data Session data
	 */
	public function getActions($id=1) {

		$this->db->select('sessionID');
		$this->db->distinct();

		$query = $this->db->get('actions');

		foreach ($query->result() as $session) {
			$sessionID[] = $session->sessionID; 
		}

		$data['all_sessions'] = $sessionID;
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

		$this->load->helper('file');

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
	 * Get user level
	 *
	 * @param  int 	 $id     Exercise ID
	 * @return array $levels Exercise levels (0/1)
	 */
	public function getUserLevel($id) {

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
	 * Clear exercises from session
	 *
	 * @param  int 	$subtopicID Subtopic ID
	 * @return void
	 */
	public function clearExercises($subtopicID) {

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
	 * Get exercise level
	 *
	 * @param  int $id        Exercise ID
	 * @param  int $level     Exercise level
	 * @return int $level_new Modified Exercise level 
	 */
	public function getExerciseLevel($id, $level) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise = $query->result()[0];
		$level_max = $exercise->level;

		if (isset($this->session->userdata('results')[$id])) {
			$level_user = $this->session->userdata('results')[$id];
			$level_new = min($level_max, $level_user+1);
		} else {
			if (!$level) {
				$level_new = 1;
			} else {
				$level_new = $level;
			}
		}

		return $level_new;
	}

	/**
	 * Update session
	 *
	 * @param  int $id     Exercise/subtopic ID
	 * @param  int $method Practice method (exercise/subtopic)
	 * @return void
	 */
	public function updateSession($id) {

		if (NULL !== $this->input->get('action')) {
			$action = $this->input->get('action');
		} else {
			$action = '';
		}

		if (NULL !== $this->input->get('method')) {
			$method = $this->input->get('method');
		} else {
			$method = '';
		}

		if ($method) {
			$this->session->set_userdata('method', $method);
			$this->session->set_userdata('goal', $id);
		}

		if ($action == 'add') {

			if (NULL !== $this->session->userdata('todo_list')) {
				$todo_list = $this->session->userdata('todo_list');
				$todo_list[] = $id;
				$this->session->set_userdata('todo_list', $todo_list);
			}

		} elseif ($action == 'delete') {

			$todo_list = $this->session->userdata('todo_list');
			$reversed = array_reverse($todo_list);
			if ($reversed[0] == $id) {
				$todo_list_new = array_pop($todo_list);
				$this->session->set_userdata('todo_list', $todo_list_new);
			}

		} elseif ($action == 'restart') {

			$this->session->unset_userdata('results');
			
		}
		
		return;
	}
}

?>