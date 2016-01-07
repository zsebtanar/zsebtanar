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
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return void
	 */
	public function Main($classID=NULL, $topicID=NULL) {

		$data = $this->Html->MainData($classID, $topicID);

		$this->load->view('Template', $data);
		
		if ($this->session->userdata('Logged_in')) {
			// $this->Session->PrintInfo();
		}
	}

	/**
	 * View subtopic
	 *
	 * @param int $subtopicID Subtopic id
	 * @param int $questID    Quest id
	 *
	 * @return	void
	 */
	public function Subtopic($subtopicID=NULL, $questID=NULL) {

		$data = $this->Html->SubtopicData($subtopicID);

		$data['questID'] = $questID;
		$data['subtopicID'] = $subtopicID;

		$this->load->view('Template', $data);
		
		if ($this->session->userdata('Logged_in')) {
			// $this->Session->PrintInfo();
		}
	}

	/**
	 * View exercise
	 *
	 * @param int $id    Exercise id
	 * @param int $level Exercise level
	 *
	 * @return	void
	 */
	public function Exercise($id=1, $level=NULL) {

		$data = $this->Html->ExerciseData($id, $level);

		$this->load->view('Template', $data);

		$type = 'exercise';

		if ($this->session->userdata('Logged_in')) {
			// $this->Session->PrintInfo();
		}
	}
}

?>