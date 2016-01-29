<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_controller {

	/**
	 * Update Database
	 *
	 * @param string $type View type (exercise/subtopic)
	 * @param int    $id   Exercise/subtopic id
	 *
	 * @return void
	 */
	public function Database($type=NULL, $id=NULL) {

		$this->load->model('Session');

		if ($this->Session->CheckLogin()) {

			// unset user data in session
			$this->Session->UnsetUserData();

			// prepare tables
			$this->load->model('Database');
			$this->Database->DropTables();
			$this->Database->CreateTables();

			// read data from file
			$data = $this->Database->ReadFile('resources/data.json');
			$this->Database->InsertData($data);

		}

		// Print exercises
		// $exercises = $this->session->userdata('exercises');
		// print_r($exercises);

		// redirect page
		$this->load->helper('url');

		if ($type && $id) {
			header('Location:'.base_url().'view/'.$type.'/'.$id);
		} else {
			header('Location:'.base_url().'view/main/');
		}
	}
}

?>