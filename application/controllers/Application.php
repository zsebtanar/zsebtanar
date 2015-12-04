<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends CI_controller {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		parent::__construct();

		$this->load->helper('url');

		return;
	}

	/**
	 * Search keyword in database (AJAX)
	 *
	 * @param string $keyword Keyword (from REQUEST)
	 *
	 * @return void
	 */
	public function Search() {
		
		$this->load->model('Database');
		$keyword = $this->input->get('keyword');
		$results = $this->Database->Search($keyword);
		echo json_encode($results);
	}

	/**
	 * Check exercise answer (AJAX)
	 *
	 * @param array $answer Answer data (from REQUEST)
	 *
	 * @return void
	 */
	public function CheckAnswer() {

		$this->load->model('Exercises');
		$answer = $this->input->get('answer');
		$result = $this->Exercises->CheckAnswer($answer);
		echo json_encode($result);
	}

	/**
	 * Clear results for subtopic
	 *
	 * Clears all results of user from session.
	 *
	 * @param int $subtopicID Subtopic id
	 *
	 * @return void
	 */
	public function ClearResults($subtopicID) {

		$this->load->model('Session');
		$this->Session->clearResults($subtopicID);
		header('Location:'.base_url().'view/subtopic/'.$subtopicID);
	}

	/**
	 * Delete sessions
	 *
	 * Delete all session activities from database
	 *
	 * @param int $subtopicID Subtopic id
	 *
	 * @return void
	 */
	public function DeleteSessions() {

		$this->db->empty_table('actions'); 
		header('Location:'.base_url().'view/subtopic/');
	}

	/**
	 * Set goal
	 *
	 * User can define whether he wants to practice an exercise or a whole subject.
	 * Goal is recorded in session. Next exercise is based on this.
	 *
	 * @param string $method Learning method (exercise/subtopic)
	 * @param int    $id     Exercise/Subtopic id
	 *
	 * @return void
	 */
	public function SetGoal($method, $id) {

		$this->load->model('Exercises');

		$this->session->set_userdata('method', $method);
		$this->session->set_userdata('goal', $id);
		$this->session->set_userdata('todo_list', []);

		if ($method == 'exercise') {
			$id_next = $id;
		} elseif ($method == 'subtopic') {
			$id_next = $this->Exercises->IDNextSubtopic();
		}

		header('Location:'.base_url().'view/exercise/'.$id_next);
	}

	/**
	 * Export sessions
	 *
	 * Exports selected session activities into csv file.
	 *
	 * @param int $subtopicID Subtopic id
	 *
	 * @return void
	 */
	public function ExportSession($id) {
		
		$this->load->dbutil();
		$this->load->helper('file');

		$query = $this->db->get_where('actions', array('sessionID' => $id));

		$data = $this->dbutil->csv_from_result($query);
		$path = './resources/saved_sessions/session_'.date('m-d-Y_H-i-s').'.csv';

		if (!write_file($path, $data)) {
			show_error('Unable to write the file');
		}

		header('Location:'.base_url().'view/subtopic/');
	}

	/**
	 * Log in to website
	 *
	 * @param string $password Password
	 *
	 * @return void
	 */
	public function Login() {

		$password = $this->input->get('password');

		if ($password == 'zst') {

			$this->session->set_userdata('Logged_in', TRUE);
			$status = 'PASSWORD_OK';

		} elseif (+$password) {

			$status = 'INCORRECT_PASSWORD';

		} else {

			$status = 'PASSWORD_MISSING';
		}

		echo json_encode($status);
	}
}

?>