<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends CI_controller {

	public function Search() {
		
		$this->load->model('Database');

		$keyword = $this->input->post('keyword');
		$results = $this->Database->Search($keyword);

		print_r($results);
	}
}

?>