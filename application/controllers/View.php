<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_controller {

	public function Page($id=NULL, $level=NULL) {
		
		$this->session->set_userdata('Write_statistics', TRUE);

		// Write statistics
		$this->load->model('Statistics');
		$this->Statistics->Write('resources/statistics.xlsx');
	}
}

?>