<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grafok_ismerosok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('11/Grafok/Ismerosok', NULL, 'Ismerosok');
		$data = $CI->Ismerosok->Generate($level);

		return $data;
	}
}

?>