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
		$this->load->helper('url');
		$this->load->model('Html');
		$this->load->model('Database');

		// Write statistics
		$this->load->model('Statistics');
		$this->Statistics->Write('resources/statistics.xlsx');

		$this->load->view('Template');

	}
	

	public function Page($id=NULL) {

		$data['html'] = $this->Html->printNavBarMenu();
		$data['refresh_icon'] = $this->Html->printRefreshIcon($id);
		$this->load->view('NavBar', $data);

		$data = $this->Html->printPageTitle($id, 'subtopic');
		$this->load->view('PageTitle', $data);

		if (!$id) {
			$this->load->view('Search');
		} else {
			$data['exercise_list'] = $this->Database->getExercises($id);
			$this->load->view('ExerciseList', $data);		
		}

		$this->load->view('Footer');
	}

	public function Exercise($id=NULL) {

		$data['html'] = $this->Html->printNavBarMenu();
		$data['refresh_icon'] = $this->Html->printRefreshIcon($id);
		$this->load->view('NavBar', $data);

		$data = $this->Html->printPageTitle($id, 'exercise');
		$this->load->view('ExerciseTitle', $data);

		$this->load->model('Exercises');
		$exercise = $this->Database->getExercise($id);
		$label = $exercise->label;

		$data = $this->Exercises->$label();
		$data['exercise'] = $exercise;
		$data['youtube'] = $exercise->youtube;
		$this->load->view('Exercise', $data);

		$this->load->view('Footer');
	}
}

?>