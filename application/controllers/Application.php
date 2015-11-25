<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends CI_controller {

	public function Search() {
		
		$this->load->model('Database');
		$keyword = $this->input->post('keyword');
		$results = $this->Database->Search($keyword);
		echo json_encode($results);
	}

	public function CheckAnswer() {

		$this->load->model('Exercises');
		$answer = $this->input->GET('answer');
		$result = $this->Exercises->CheckAnswer($answer);
		echo json_encode($result);
	}
}

?>