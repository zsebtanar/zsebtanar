<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Valoszinuseg_oszthatosag {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define probability of divisibility
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('11/Valoszinuseg/Oszthatosag', NULL, 'Oszthatosag');
		$data = $CI->Oszthatosag->Generate($level);

		return $data;
	}
}

?>