<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_controller {

	/**
	 * Update Database
	 *
	 * @return void
	 */
	public function Database() {

		// unset user data in session
		$this->load->model('Session');
		$this->Session->UnsetUserData();

		// prepare tables
		$this->load->model('Database');
		$this->Database->DropTables();
		$this->Database->CreateTables();

		// read data from file
		$data = $this->Database->ReadFile('resources/data.json');
		$this->Database->InsertData($data);

		// redirect page
		$this->Database->Redirect();
	}
}

?>