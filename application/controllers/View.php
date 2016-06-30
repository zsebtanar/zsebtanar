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
	 * @param string $hash          Exercise hash
	 *
	 * @return	void
	 */
	public function Subtopic($classlabel=NULL, $subtopiclabel=NULL, $hash=NULL) {

		$data = $this->Html->SubtopicData($classlabel, $subtopiclabel);

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
	 * @param string $hash Exercise hash
	 *
	 * @return	void
	 */
	public function Exercise($classlabel, $subtopiclabel, $exerciselabel, $hash=NULL) {

		$this->load->model('Database');

		$exerciseID = $this->Database->ExerciseID($classlabel, $subtopiclabel, $exerciselabel);

		if ($exerciseID) {

			$data = $this->Html->ExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID);

			$this->load->view('Template', $data);

		} else {

			header('Location:'.base_url().'view/main/');

		}
	}
}

?>