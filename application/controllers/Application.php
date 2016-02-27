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

	/**
	 * Get hint for exercise
	 *
	 * @param string $hash Exercise hash
	 * @param int    $id   Order of hint
	 *
	 * @return void
	 */
	public function GetHint($hash, $id=NULL) {

		$this->load->model('Exercises');
		$result = $this->Exercises->GetHint($hash, $id);
		echo json_encode($result);
	}

	/**
	 * Clear results
	 *
	 * @param string $type View type (exercise/subtopic)
	 * @param int    $id   Exercise/subtopic id
	 *
	 * @return void
	 */
	public function ClearResults($type=NULL, $id=NULL) {

		$this->session->unset_userdata('levels');
		$this->session->unset_userdata('subtopics');
		$this->session->unset_userdata('points');
		$this->session->unset_userdata('shields');
		$this->session->unset_userdata('trophies');
		$this->session->unset_userdata('exercise');

		if ($type && $id) {
			header('Location:'.base_url().'view/'.$type.'/'.$id);
		} else {
			header('Location:'.base_url().'view/main/');
		}

		return;
	}
}

?>