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
		$this->load->model('Exercises');
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
	public function Subtopic($id=NULL) {

		$this->load->view('Template');

		$this->session->unset_userdata('method');
		$this->session->unset_userdata('goal');
		$this->session->unset_userdata('todo_list');

		$type = 'subtopic';

		$data = $this->Html->NavBarMenu($id, $type);
		$this->load->view('NavBar', $data);

		$data = $this->Html->Title($id, $type);
		$data['id_next'] = $this->Exercises->IDNextSubtopic($id);

		$data['id'] = $id;
		$this->load->view('Title', $data);

		if (!$id) {
			$this->load->view('Search');
		} else {
			$data = $this->Exercises->getExerciseList($id);
			$this->load->view('ExerciseList', $data);		
		}

		$this->load->view('Footer');

		$this->Session->PrintInfo();

	}

	public function Exercise($id=1, $level=NULL) {

		$this->Session->UpdateTodoList($id);

		$type = 'exercise';

		$this->load->view('Template');

		if (!$level) {
			$level = $this->Session->getExerciseLevelNext($id);
		}

		$data = $this->Html->NavBarMenu($id, $type);
		$this->load->view('NavBar', $data);

		$data = $this->Html->Title($id, $type);
		$this->load->view('Title', $data);

		$data = $this->Exercises->getExerciseData($id, $level);
		$this->load->view('Exercise', $data);

		$this->load->view('Footer');

		$this->Session->PrintInfo();

	}

	/**
	 * View activities
	 *
	 * Display summary of all sessions or specific session.
	 *
	 * @param int $id Session ID
	 *
	 * @return void
	 */
	public function Activities($id=NULL) {

		$this->load->view('Template');

		$data = $this->Session->getSessions($id);

		$this->load->view('Session', $data);
		$this->load->view('Footer');

	}
}

?>