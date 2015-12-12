<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_controller {

	/**
	 * Update Database
	 *
	 * @return void
	 */
	public function Database() {

		$this->load->model('Database');
		$this->load->model('Session');

		$this->session->set_userdata('Write_statistics', TRUE);

		$data = $this->Database->ReadFile('resources/data.json');

		if ($this->session->userdata('Logged_in')) {
			
			$data = $this->Database->ReadFile('resources/data.json');

			$this->Database->DropTables();
			$this->Database->CreateTables();

			$this->Database->InsertData($data);
			$this->Database->UnsetUserData();

		}

		$this->Database->InsertData($data);
		$this->Session->UnsetUserData();
		$this->Database->Redirect();
	}
}

?>