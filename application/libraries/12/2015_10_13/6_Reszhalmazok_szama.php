<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 6_Reszhalmazok_szama {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define total number of subsets of a set
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('9/Halmazmuveletek/Reszhalmazok', NULL, 'Reszhalmazok');
		$data = $CI->Reszhalmazok->Generate($level);

		return $data;
	}
}

?>