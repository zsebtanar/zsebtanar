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

	public function DeleteSessions() {

		$this->load->helper('url');
		$this->db->empty_table('actions'); 
		header('Location:'.base_url().'view/page/');
	}

	public function ExportSession($id) {
		
		$this->load->dbutil();
		$this->load->helper('url');
		$this->load->helper('file');

		$query = $this->db->get_where('actions', array('sessionID' => $id));

		$data = $this->dbutil->csv_from_result($query);
		$path = './resources/saved_sessions/session_'.date('m-d-Y_H-i-s').'.csv';

		print_r($data);
		print_r($path);

		if (!write_file($path, $data)) {
			show_error('Unable to write the file');
		}

		header('Location:'.base_url().'view/page/');
	}
}

?>