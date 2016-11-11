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
	 * Get random exercise
	 *
	 * @param string $classLabe Class label
	 *
	 * @return void
	 */
	public function GetRandomExercise($classLabel=NULL) {

		$this->load->model('Database');

		$link = $this->Database->RandomExerciseLink($classLabel);

		header('Location:'.$link);
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
	 * Setup system
	 *
	 * @return void
	 */
	public function Setup() {

		$this->load->model('Session');
		$this->load->model('Setup');

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

		header('Location:'.base_url());
	}

	/**
	 * Get exercises for specific tag (AJAX)
	 *
	 * @param string $tag Exercise tag (from REQUEST)
	 *
	 * @return void
	 */
	public function GetTagExercises() {

		$this->load->model('Database');

		if (isset($_GET['term'])) {
			$tag = strtolower($_GET['term']);
			$result = $this->Database->GetTagExercises($tag);
			echo json_encode($result);
		}
	}

	/**
	 * Save search tag (AJAX)
	 *
	 * When user uses the search bar for the first time,
	 * the program will automatically sends the search tag via email.
	 *
	 * @param string $search_tag Search tag (from REQUEST)
	 *
	 * @return void
	 */
	public function SaveSearchTag() {

		$_SESSION['show_search_message'] = TRUE;
		$_SESSION['first_search_done'] = TRUE;

		$this->load->library('email');

		if (isset($_GET['search_tag']) && base_url() == 'http://zsebtanar.hu/') {

			$search_tag = $_GET['search_tag'];

			$this->email->from('noreply@zsebtanar.hu', 'Zsebtanár');
			$this->email->to('szaboviktor1988@gmail.com');

			$this->email->subject('Új keresés kifejezés a zsebtanar.hu-n!');
			$this->email->message($search_tag);

			$this->email->send();

		}

		echo json_encode(TRUE);
	}
}

?>