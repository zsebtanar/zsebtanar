<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osztas_teszt {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Test for square root
	function Generate($level) {

		$CI =& get_instance();

		$type = rand(1,3);

		if ($type == 1) {

			$CI->load->library('9/Oszthatosag/Osztas_teszt1', NULL, 'Teszt1');
			$data = $CI->Teszt1->Generate($level);

		} elseif ($type == 2) {
		
			$CI->load->library('9/Oszthatosag/Osztas_teszt2', NULL, 'Teszt2');
			$data = $CI->Teszt2->Generate($level);

		} else {

			$CI->load->library('9/Oszthatosag/Osztas_teszt3', NULL, 'Teszt3');
			$data = $CI->Teszt3->Generate($level);

		}

		return $data;
	}
}

?>