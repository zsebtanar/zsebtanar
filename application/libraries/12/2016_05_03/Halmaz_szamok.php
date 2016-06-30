<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Halmaz_szamok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define members of intersection/union/difference of sets
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('9/Halmazmuveletek/Szamok', NULL, 'Szamok');
		$data = $CI->Szamok->Generate($level);

		return $data;
	}
}

?>