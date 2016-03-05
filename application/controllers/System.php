<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends CI_controller {

	/**
	 * Setup system
	 *
	 * @param string $type View type (exercise/subtopic)
	 * @param int    $id   Exercise/subtopic id
	 *
	 * @return void
	 */
	public function Setup($type=NULL, $id=NULL) {

		// setup tables
		$this->load->model('Setup');
		$this->Setup->DropTables();
		$this->Setup->CreateTables();

		// read data from file
		$data = $this->Setup->ReadFile('resources/data.json');
		$this->Setup->InsertData($data);

		$this->load->helper('url');

		header('Location:'.base_url().'view/main/');
	}

	/**
	 * Update system
	 *
	 * @param string $type View type (exercise/subtopic)
	 * @param int    $id   Exercise/subtopic id
	 *
	 * @return void
	 */
	public function Update($type=NULL, $id=NULL) {

		$this->load->model('Session');
		$this->load->model('Setup');

		if ($this->Session->CheckLogin()) {

			// unset user data in session
			$this->Session->UnsetUserData();

			// prepare tables
			$this->Setup->DropTables();
			$this->Setup->CreateTables();

			// read data from file
			$data = $this->Setup->ReadFile('resources/data_test.json');
			$this->Setup->InsertData($data);

		}

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