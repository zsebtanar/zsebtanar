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

		// Load models
		$this->load->helper('url');
		$this->load->model('Html');
		$this->load->model('Session');
		$this->load->model('Statistics');

		// Write statistics of website content
		$this->Statistics->Write('resources/statistics.xlsx');

		// Set session ID
		$this->Session->setSessionID();
	}

	/**
	 * View subtopic
	 *
	 * @param  int $id Subtopic id
	 * @return	void
	 */
	public function Subtopic($id=NULL, $type = 'subtopic') {

		$this->load->view('vTemplate');
		$this->Session->setSessionID();

		print_r($_SESSION);

		$this->Session->recordAction($id, $type);

		$data = $this->Html->printNavBarMenu($id, $type);
		$this->load->view('vNavBar', $data);

		$data = $this->Html->printTitle($id, $type);
		$data['button'] = $this->Html->getNextExerciseSubtopic($id);
		$this->load->view('vTitle', $data);

		if (!$id) {
			$this->load->view('vSearch');
		} else {
			$data = $this->Html->getExerciseList($id);
			$this->load->view('vExerciseList', $data);		
		}

		$this->load->view('vFooter');
	}

	public function Exercise($id=1, $level=NULL) {

		$type = 'exercise';

		$this->load->view('vTemplate');

		if (!$level) {
			$level = $this->Session->getExerciseLevelNext($id);
		}

		$this->Session->recordAction($id, $type, $level);

		print_r($_SESSION);

		$data = $this->Html->printNavBarMenu($id, $type);
		$this->load->view('vNavBar', $data);


		$data = $this->Html->printTitle($id, $type);
		$this->load->view('vTitle', $data);


		$data = $this->Html->getExerciseData($id, $level);
		$this->load->view('vExercise', $data);

		$this->load->view('vFooter');
	}

	public function Session($type='database', $id=NULL) {

		$this->load->view('vTemplate');

		if ($type == 'database') {
			$data = $this->Session->getActions($id);
		} elseif ($type == 'import') {
			$data = $this->Session->getSavedSessions($id);
		}

		$this->load->view('vSession', $data);
		$this->load->view('vFooter');

	}
}

?>