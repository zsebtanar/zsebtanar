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
		
		if ($this->Session->CheckLogin()) {
			$this->Session->PrintInfo();
		}
	}

	/**
	 * View subtopic
	 *
	 * @param int $subtopicID Subtopic id
	 * @param int $exerciseID Exercise id
	 *
	 * @return	void
	 */
	public function Subtopic($subtopicID=NULL, $exerciseID=NULL) {

		$data = $this->Html->SubtopicData($subtopicID, $exerciseID);

		$data['exerciseID'] = $exerciseID;
		$data['subtopicID'] = $subtopicID;

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
	public function Exercise($id, $round=NULL) {

		$this->load->model('Database');

		if ($this->Database->ExerciseExists($id)) {

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