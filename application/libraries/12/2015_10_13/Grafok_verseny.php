<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grafok_verseny {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define degree of unknown point of graph
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('11/Grafok/Verseny', NULL, 'Verseny');
		$data = $CI->Verseny->Generate($level);

		return $data;
	}
}

?>