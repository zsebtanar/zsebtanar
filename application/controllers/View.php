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
		$this->load->model('Session');

		// Write statistics
		$this->load->model('Statistics');
		$this->Statistics->Write('resources/statistics.xlsx');
		
		$this->load->view('Template');

	}

	public function Page($id=NULL) {

		$type = 'page';

		$this->Session->recordAction($id, $type);

		$data = $this->Html->printNavBarMenu($id, $type);
		$this->load->view('NavBar', $data);

		$data = $this->Html->printPageTitle($id, $type);
		$this->load->view('Title', $data);

		if (!$id) {
			$this->load->view('Search');
		} else {
			$data = $this->Html->getExercises($id);
			$this->load->view('ExerciseList', $data);		
		}

		$this->load->view('Footer');
	}

	public function Exercise($id=1, $level=1) {

		$level = min($level, 3);
		$type = 'exercise';

		$this->Session->recordAction($id, $type, $level);

		$data = $this->Html->printNavBarMenu($id, $type);
		$this->load->view('NavBar', $data);


		$data = $this->Html->printPageTitle($id, $type);
		$this->load->view('Title', $data);


		$data = $this->Html->getExerciseData($id, $level);
		$this->load->view('Exercise', $data);

		$this->load->view('Footer');
	}

	public function Login($password) {

		if ($password = 'zst') {

			$this->session->set_userdata('Logged_in', TRUE);
			header('Location:'.base_url().'view/page/');

		}
	}

	public function Session($type='database', $id=NULL) {

		if ($type == 'database') {
			$data = $this->Session->getActions($id);
		} elseif ($type == 'import') {
			$data = $this->Session->getSavedSessions($id);
		}

		$this->load->view('Session', $data);
		$this->load->view('Footer');

	}
}

?>