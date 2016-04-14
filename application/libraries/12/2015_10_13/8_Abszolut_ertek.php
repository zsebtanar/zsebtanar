<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 8_Abszolut_ertek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define solution of equation for absolute values
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('5/Abszolut_ertek/Egyenlet', NULL, 'Egyenlet');
		$data = $CI->Egyenlet->Generate($level);

		return $data;
	}
}

?>