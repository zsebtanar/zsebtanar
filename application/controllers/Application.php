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
	 * Clear results for quest
	 *
	 * Clears all results for specific quest from session.
	 *
	 * @param int $subtopicID Subtopic id
	 * @param int $questID    Quest id
	 *
	 * @return void
	 */
	public function ClearResults($subtopicID, $questID) {

		$this->load->model('Session');
		$this->Session->clearResults($questID);

		header('Location:'.base_url().'view/subtopic/'.$subtopicID.'/'.$questID);
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
		} elseif ($method == 'quest') {
			$id_next = $this->Exercises->IDNextQuest();
		}

		header('Location:'.base_url().'view/exercise/'.$id_next);
	}

	/**
	 * Log in to website
	 *
	 * @param string $password Password
	 *
	 * @return void
	 */
	public function Login($password) {

		if ($password == 'zst') {

			$this->session->set_userdata('Logged_in', TRUE);

		}

		header('Location:'.base_url().'view/main/');

		return;
	}

	/**
	 * Log out from website
	 *
	 * @return void
	 */
	public function Logout() {

		$this->session->set_userdata('Logged_in', FALSE);

		header('Location:'.base_url().'view/main/');

		return;
	}
}

?>