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
		$this->load->model('Html');
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

		$this->Session->ClearSession();
		$data = $this->Html->SubtopicData($id);

		$this->load->view('Template', $data);
		
		if ($this->session->userdata('Logged_in')) {
			$this->Session->PrintInfo();
		}
	}

	public function Exercise($id=1, $level=NULL) {

		$this->Session->UpdateTodoList($id);

		$data = $this->Html->ExerciseData($id, $level);

		$this->load->view('Template', $data);

		$type = 'exercise';

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

		$this->load->model('Activities');

		if (!$sessionID) {

			$data['type'] = 'sessions';
			$data['data']['sessions'] = $this->Activities->getSessions();
			
		} elseif (!$questID) {
			$data['type'] = 'quests';
			$data['data']['quests'] = $this->Activities->getQuests($sessionID);
			$data['data']['sessionID'] = $sessionID;

		} else {

			$data['type'] = 'actions';
			$data['data']['actions'] = $this->Activities->getActions($questID);
			$data['data']['sessionID'] = $sessionID;
			$data['data']['questID'] = $questID;
			$data['data']['questName'] = $this->Activities->getQuestName($questID);

		}

		$this->load->view('Template', $data);
	}
}

?>