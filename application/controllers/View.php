<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_controller {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {

		parent::__construct();

		// load models
		$this->load->model('Html');
		$this->load->model('Session');

	}

	/**
	 * View main page
	 *
	 * @param string $hash Exercise hash
	 *
	 * @return void
	 */
	public function Main($hash=NULL) {

		$data = $this->Html->MainData();

		if ($hash) {
			$this->Session->DeleteExerciseData($hash);
		}

		$this->load->view('Template', $data);
		
		if ($this->Session->CheckLogin()) {
			$this->Session->PrintInfo();
		}
	}

	/**
	 * View subtopic
	 *
	 * @param string $hash          Exercise hash
	 * @param int    $subtopiclabel Subtopic label
	 *
	 * @return	void
	 */
	public function Subtopic($subtopiclabel=NULL, $hash=NULL) {

		$subtopicID = $this->Database->SubtopicID($subtopiclabel);

		if ($hash) {
			$this->Session->DeleteExerciseData($hash);
		}

		$data = $this->Html->SubtopicData($subtopicID);

		$this->load->view('Template', $data);
		
		if ($this->Session->CheckLogin()) {
			$this->Session->PrintInfo();
		}
	}

	/**
	 * View exercise
	 *
	 * @param int    $id   Exercise id
	 * @param string $hash Exercise hash
	 *
	 * @return	void
	 */
	public function Exercise($label, $hash=NULL) {

		$this->load->model('Database');

		if ($hash) {
			$this->Session->DeleteExerciseData($hash);
		}

		$id = $this->Database->ExerciseID($label);

		if ($this->Database->ExerciseExists($label)) {

			$data = $this->Html->ExerciseData($id);

			$this->load->view('Template', $data);

			$type = 'exercise';

			if ($this->Session->CheckLogin()) {
				$this->Session->PrintInfo();
			}

		} else {
			header('Location:'.base_url().'view/main/');
		}
	}
}

?>