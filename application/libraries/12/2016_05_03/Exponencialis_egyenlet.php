<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exponencialis_egyenlet {

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
		$CI->load->library('11/Hatvany/Egyenlet_exp', NULL, 'Egyenlet_exp');
		$data = $CI->Egyenlet_exp->Generate($level);

		return $data;
	}
}

?>