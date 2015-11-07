<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_controller {

	public function Database() {

		$dbname = $this->db->database;
		
		$this->load->model('Database');
		$this->Database->Drop($dbname);
		$this->Database->Create($dbname);
		$this->Database->Connect($dbname);

		$this->load->model('Tables');
		$this->Tables->Drop('classes');
		$this->Tables->Create('classes');

	}
}

?>