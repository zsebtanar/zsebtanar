<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_controller {

	public function Page($id=NULL, $level=NULL) {
		
		// Write statistics
		$this->load->model('Statistics');
		$this->Statistics->Write('resources/statistics.xlsx');

		// Load page
		$this->load->helper('url');
		$this->load->model('Html');

		$this->load->view('Header');
		$this->load->view('GoogleAnalytics');

		$data['html'] = $this->Html->printNavBarMenu();
		$data['refresh_icon'] = $this->Html->printRefreshIcon($id, $level);
		$this->load->view('NavBar', $data);

		$this->load->view('Modal/Search');
		$this->load->view('Modal/Info');
		$this->load->view('Modal/Youtube');

		$data = $this->Html->printPageTitle($id);
		$this->load->view('PageTitle', $data);

		$this->load->view('Footer');
	}
}

?>