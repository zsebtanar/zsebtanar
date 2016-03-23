<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_controller {

	/**
	 * Class constructor
	 *
	 * @return	void
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
	 * @return void
	 */
	public function Main() {

		$data = $this->Html->MainData();

		$this->load->view('Template', $data);
		
		if ($this->Session->CheckLogin()) {
			$this->Session->PrintInfo();
		}
	}

	/**
	 * View subtopic
	 *
	 * @param int $subtopiclabel Subtopic label
	 *
	 * @return	void
	 */
	public function Subtopic($subtopiclabel=NULL) {

		$subtopicID = $this->Database->SubtopicID($subtopiclabel);

		$data = $this->Html->SubtopicData($subtopicID);

		$this->load->view('Template', $data);
		
		if ($this->Session->CheckLogin()) {
			$this->Session->PrintInfo();
		}
	}

	/**
	 * View exercise
	 *
	 * @param int $id    Exercise id
	 * @param int $round Exercise round
	 *
	 * @return	void
	 */
	public function Exercise($label, $round=NULL) {

		$this->load->model('Database');

		$id = $this->Database->ExerciseID($label);

		if ($this->Database->ExerciseExists($label)) {

			$data = $this->Html->ExerciseData($id, $round);

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