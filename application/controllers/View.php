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

		// print_r($this->session->userdata('method').'<br />');
		// print_r($this->session->userdata('goal').'<br />');
		// print_r($this->session->userdata('todo_list'));
		// print_r(microtime());
	}

	/**
	 * View subtopic
	 *
	 * @param  int $id Subtopic id
	 * @return	void
	 */
	public function Subtopic($id=NULL, $type = 'subtopic') {

		$this->load->view('Template');
		$this->Session->setSessionID();

		$this->session->unset_userdata('method');
		$this->session->unset_userdata('goal');
		$this->session->unset_userdata('todo_list');

		$this->Session->recordAction($id, $type);

		$data = $this->Html->printNavBarMenu($id, $type);
		$this->load->view('NavBar', $data);

		$data = $this->Html->printTitle($id, $type);
		$data['button'] = $this->Html->getNextExerciseSubtopic($id);
		$this->load->view('Title', $data);

		if (!$id) {
			$this->load->view('Search');
		} else {
			$data = $this->Html->getExerciseList($id);
			$this->load->view('ExerciseList', $data);		
		}

		$this->load->view('Footer');
	}

	public function Exercise($id=1, $level=NULL) {

		$type = 'exercise';

		$this->load->view('Template');

		if (!$level) {
			$level = $this->Session->getExerciseLevelNext($id);
		}

		$this->Session->recordAction($id, $type, $level);

		$data = $this->Html->printNavBarMenu($id, $type);
		$this->load->view('NavBar', $data);


		$data = $this->Html->printTitle($id, $type);
		$this->load->view('Title', $data);


		$data = $this->Html->getExerciseData($id, $level);
		$this->load->view('Exercise', $data);

		$this->load->view('Footer');
	}

	public function Session($type='database', $id=NULL) {

		$this->load->view('Template');

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