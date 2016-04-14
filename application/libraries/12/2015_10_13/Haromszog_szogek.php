<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haromszog_szogek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	// Define triangle angle based on two given angles
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('7/Sikgeometria/Haromszog_szogei', NULL, 'Haromszog_szogei');
		$data = $CI->Haromszog_szogei->Generate($level);

		return $data;
	}
}

?>