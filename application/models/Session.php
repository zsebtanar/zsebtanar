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
		}

		return;
	}

	/**
	 * Record action
	 *
	 * @param  int 	  $id     Subtopic/Exercise ID
	 * @param  string $type   View type (page/exercise)
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

		if ($result == 'CORRECT') {

			$newdata = array('result_'.$id => $level);
			$this->session->set_userdata($newdata);

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

		if (NULL !== $this->session->userdata('result_'.$id)) {
			$user_level = $this->session->userdata('result_'.$id);
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
}

?>