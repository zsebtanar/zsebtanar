<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Square_root_test {

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

			$CI->load->library('Square_root_test1');
			$data = $CI->square_root_test1->Generate($level);

		} elseif ($type == 2) {
		
			$CI->load->library('Square_root_test2');
			$data = $CI->square_root_test2->Generate($level);

		} else {

			$CI->load->library('Square_root_test3');
			$data = $CI->square_root_test3->Generate($level);

		}

		return $data;
	}
}

?>