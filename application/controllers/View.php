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

		// Start session
		$this->Session->startSession();
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

		if ($this->session->userdata('Logged_in')) {
			$this->Session->PrintInfo();
		}

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

		if ($this->session->userdata('Logged_in')) {
			$this->Session->PrintInfo();
		}

	}

	/**
	 * View activities
	 *
	 * Display summary sessions/quest/actions.
	 *
	 * @param int $sessionID Session ID
	 * @param int $questID Quest ID
	 *
	 * @return void
	 */
	public function Activities($sessionID=NULL, $questID=NULL) {

		$this->load->view('Template');

		if (!$sessionID) {
			$data['sessions'] = $this->Session->getSessions();
			$this->load->view('Sessions', $data);
		} elseif (!$questID) {
			$data['quests'] = $this->Session->getQuests($sessionID);
			$data['sessionID'] = $sessionID;
			$this->load->view('Quests', $data);
		} else {
			$data['actions'] = $this->Session->getActions($questID);
			$data['sessionID'] = $sessionID;
			$data['questID'] = $questID;
			$data['questName'] = $this->Session->getQuestName($questID);
			$this->load->view('Actions', $data);
		}
		
		$this->load->view('Footer');

	}
}

?>