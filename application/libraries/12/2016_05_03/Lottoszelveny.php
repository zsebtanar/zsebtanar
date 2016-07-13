<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lottoszelveny {

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
		$CI->load->library('11/Kombinatorika/Lotto', NULL, 'Exercise');
		$data = $CI->Exercise->Generate($level);

		return $data;
	}
}

?>