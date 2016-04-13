<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pentathlon {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	// Generate random number between 1 and 20
	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 2) {

			$CI->load->library('Pentathlon_points1');
			$data = $CI->pentathlon_points1->Generate($level);

		} elseif ($level <= 4) {

			$CI->load->library('Pentathlon_points2');
			$data = $CI->pentathlon_points2->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('Pentathlon_number_set1');
			$data = $CI->pentathlon_number_set1->Generate($level);
			
		} elseif ($level <= 8) {

			$CI->load->library('Pentathlon_number_set2');
			$data = $CI->pentathlon_number_set2->Generate($level);

		} else {

			$CI->load->library('Pentathlon_variation');
			$data = $CI->pentathlon_variation->Generate($level);

		}

		return $data;
	}
}

?>