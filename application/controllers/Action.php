<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action extends CI_controller {

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

		$this->load->model('Check');
		$answer = $this->input->get('answer');
		$result = $this->Check->CheckAnswer($answer);
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

		header('Location:'.base_url());
	}

	/**
	 * Log out from website
	 *
	 * @return void
	 */
	public function Logout() {

		$this->session->set_userdata('Logged_in', FALSE);

		header('Location:'.base_url());
	}

	/**
	 * Get hint for exercise
	 *
	 * @param string $hash Exercise hash
	 * @param int    $id   Id of hint
	 * @param string $type Request type (prev/next)
	 *
	 * @return void
	 */
	public function GetHint($hash, $id=NULL, $type='next') {

		$this->load->model('Session');
		$result = $this->Session->GetExerciseHint($hash, $id, $type);
		echo json_encode($result);
	}

	/**
	 * Clear results
	 *
	 * @return void
	 */
	public function ClearResults() {

		$this->session->unset_userdata('levels');
		$this->session->unset_userdata('subtopics');
		$this->session->unset_userdata('points');
		$this->session->unset_userdata('shields');
		$this->session->unset_userdata('trophies');
		$this->session->unset_userdata('exercise');

		header('Location:'.base_url());
	}

	/**
	 * Setup system
	 *
	 * @return void
	 */
	public function Setup() {

		// setup tables
		$this->load->model('Setup');
		$this->Setup->DropTables();
		$this->Setup->CreateTables();

		// read data from file
		$files = scandir('resources');
		foreach ($files as $file) {
			if (preg_match('/json/', strtolower($file))) {
				$data = $this->Setup->ReadFile('resources/'.$file);
				$this->Setup->InsertData($data);
			}
		}

		header('Location:'.base_url());
	}

	/**
	 * Unset exercise data from session
	 *
	 * @param string $hash Exercise hash
	 *
	 * @return void
	 */
	public function UnsetExercise($hash=NULL) {

		$this->load->model('Session');

		if ($hash) {
			$this->Session->DeleteExerciseData($hash);
		}

		return;
	}

	/**
	 * Delete user
	 *
	 * @param int $userID User ID
	 *
	 * @return void
	 */
	public function DeleteUser($userID=NULL) {

		if ($userID) {
	
			$this->load->model('User');
			$this->User->DeleteUser($userID);
	
		}

		header('Location:'.base_url().'view/statistics');
	}

	/**
	 * Update system
	 *
	 * @param string $type View type (exercise/subtopic)
	 * @param int    $id   Exercise/subtopic id
	 *
	 * @return void
	 */
	public function Update($type=NULL, $id=NULL) {

		$this->load->model('Session');
		$this->load->model('Setup');

		if ($this->Session->CheckLogin()) {

			// unset user data in session
			$this->Session->UnsetUserData();

			// prepare tables
			$this->Setup->DropTables();
			$this->Setup->CreateTables();

			// read data from file
			$files = scandir('resources');
			foreach ($files as $file) {
				if (preg_match('/json/', strtolower($file))) {
					$data = $this->Setup->ReadFile('resources/'.$file);
					$this->Setup->InsertData($data);
				}
			}
		}

		header('Location:'.base_url());
	}

	/**
	 * Get exercise tags (AJAX)
	 *
	 * @param string $tag Exercise tag (from REQUEST)
	 *
	 * @return void
	 */
	public function GetExerciseTags() {

		$this->load->model('Database');

		if (isset($_GET['term'])) {
			$tag = strtolower($_GET['term']);
			$result = $this->Database->GetExerciseTags($tag);
		}
	}
}

?>