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
	 * @return void
	 */
	public function Main() {

		$data = $this->Html->MainData();

		if ($data) {

			$this->load->view('Template', $data);

		} else {

			header('Location:'.base_url().'view/main/');

		}
	}

	/**
	 * View subtopic
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 *
	 * @return	void
	 */
	public function Subtopic($classlabel=NULL, $subtopiclabel=NULL) {

		$data = $this->Html->SubtopicData($classlabel, $subtopiclabel);

		if ($data) {

			$this->load->view('Template', $data);

		} else {

			header('Location:'.base_url().'view/main/');
			
		}		
	}

	/**
	 * View tag
	 *
	 * @param string $tag Tag
	 *
	 * @return	void
	 */
	public function Tag($tag=NULL) {

		$data = $this->Html->TagData($tag);

		if ($data) {

			$this->load->view('Template', $data);

		} else {

			header('Location:'.base_url().'view/main/');
			
		}		
	}


	/**
	 * View exercise
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 * @param string $access 		How was this exercise accessed by user?
	 *
	 * @return	void
	 */
	public function Exercise($classlabel, $subtopiclabel, $exerciselabel, $access=NULL) {


		$this->load->model('Database');
		$this->load->model('Session');
		$this->load->model('User');

		$exerciseID = $this->Database->ExerciseID($classlabel, $subtopiclabel, $exerciselabel);

		$this->User->AddUserExercise($exerciseID, $access);

		if ($exerciseID) {

			$data = $this->Html->ExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID);

			$this->load->view('Template', $data);

		} else {

			header('Location:'.base_url().'view/main/');

		}
	}

	/**
	 * View statistics
	 *
	 * @param int $userID User ID
	 *
	 * @return	void
	 */
	public function Statistics($userID=NULL) {

		if (!$userID) {

			$data = $this->Html->Users();

		} else {

			$data = $this->Html->UserExercises($userID);

		}

		$this->load->view('Template', $data);

	}
}

?>