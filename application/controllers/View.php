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

		// Write statistics
		$this->load->model('Statistics');
		$this->Statistics->Write('resources/statistics.xlsx');

		$this->load->view('Template');

	}
	

	public function Page($id=NULL, $level=NULL) {

		$data['html'] = $this->Html->printNavBarMenu();
		$data['refresh_icon'] = $this->Html->printRefreshIcon($id, $level);
		$this->load->view('NavBar', $data);

		$data = $this->Html->printPageTitle($id, 'subtopic');
		$this->load->view('PageTitle', $data);

		if (!$id) {
			$this->load->view('Modal/Search_main');
		}

		$this->load->view('Footer');
	}

	public function Exercise($id=NULL, $level=NULL) {

		$data['html'] = $this->Html->printNavBarMenu();
		$data['refresh_icon'] = $this->Html->printRefreshIcon($id, $level);
		$this->load->view('NavBar', $data);

		$data = $this->Html->printPageTitle($id, 'exercise');
		$this->load->view('PageTitle', $data);

		$this->load->view('Footer');
	}
}

?>