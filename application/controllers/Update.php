<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_controller {

	public function Database($id=NULL, $level=NULL) {

		$this->session->set_userdata('Logged_in', TRUE);
		$this->session->set_userdata('Write_statistics', TRUE);
		
		$this->load->model('Database');
		$data = $this->Database->ReadFile('resources/data.json');

		if (!isset($id)) {
			$this->Database->DropTables();
			$this->Database->CreateTables();
		} else {
			$this->Database->DeleteFromTables($id);
		}

		$this->Database->InsertData($data, $id);
		// $this->Database->Redirect($id, $level);
	}
}

?>