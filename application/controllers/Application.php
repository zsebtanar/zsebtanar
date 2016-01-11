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
	 * Clear results
	 *
	 * @return void
	 */
	public function ClearResults() {

		$this->session->unset_userdata('levels');
		$this->session->unset_userdata('quests');
		$this->session->unset_userdata('rounds');
		$this->session->unset_userdata('points');

		header('Location:'.base_url().'view/main/');

		return;
	}
}

?>