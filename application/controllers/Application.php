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
	 * End session
	 *
	 * Drop activities from database & unset session ID
	 *
	 * @param int $subtopicID Subtopic id
	 *
	 * @return void
	 */
	public function DeleteSessions() {

		$this->db->empty_table('actions'); 
		$this->db->empty_table('quests'); 
		$this->db->empty_table('sessions');

		$this->session->unset_userdata('sessionID');

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
		$this->load->model('Session');

		$this->session->set_userdata('method', $method);
		$this->session->set_userdata('goal', $id);
		$this->session->set_userdata('todo_list', []);

		if ($method == 'exercise') {
			$id_next = $id;
		} elseif ($method == 'subtopic') {
			$id_next = $this->Exercises->IDNextSubtopic();
		}

		$this->Session->StartQuest();

		header('Location:'.base_url().'view/exercise/'.$id_next);
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

	/**
	 * Log out from website
	 *
	 * @return void
	 */
	public function Logout() {

		$this->session->set_userdata('Logged_in', FALSE);

		header('Location:'.base_url().'view/subtopic/');

		return;
	}
}

?>