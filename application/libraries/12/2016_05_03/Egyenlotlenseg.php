<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlotlenseg {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('10/Masodfoku_egyenlet/Egyenlotlenseg_grafikus', NULL, 'Egyenlet');
		$data = $CI->Egyenlet->Generate($level);

		return $data;
	}
}

?>