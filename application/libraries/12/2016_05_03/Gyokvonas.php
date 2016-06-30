<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyokvonas {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Solve equation for square root
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('10/Gyokvonas/Egyenlet1', NULL, 'Egyenlet1');
		$data = $CI->Egyenlet1->Generate($level);

		return $data;
	}
}

?>