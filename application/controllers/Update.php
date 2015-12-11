<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_controller {

	/**
	 * Update Database
	 *
	 * @return void
	 */
	public function Database() {

		$this->session->set_userdata('Write_statistics', TRUE);

		$this->load->model('Database');

		if ($this->session->userdata('Logged_in')) {
			
			$data = $this->Database->ReadFile('resources/data.json');

			$this->Database->DropTables();
			$this->Database->CreateTables();

			$this->Database->InsertData($data);
			$this->Database->UnsetUserData();

		}

		$this->Database->Redirect();
	}
}

?>