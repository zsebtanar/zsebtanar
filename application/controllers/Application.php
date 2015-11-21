<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends CI_controller {

	public function Search() {
		
		$this->load->model('Database');
		$keyword = $this->input->post('search');
		$results = $this->Database->Search($keyword);
		echo json_encode($results);
	}

	public function CheckAnswer() {

		// $this->load->model('Exercises');
		// $answer = $this->input->post('answer');
		// $result = $this->Exercises->CheckAnswer($answer);

		// if (!$result) {
		// 	echo 'A helyes megoldás: '.$_REQUEST['solution'];
		// } else {
		// 	echo 'sdfd';
		// }

		echo json_encode('adslfkjasdfkldjs');
	}
}

?>