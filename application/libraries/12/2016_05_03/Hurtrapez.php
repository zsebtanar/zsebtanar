<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hurtrapez {

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

		if ($level <= 3) {

			$CI->load->library('12/Sikidom/Hurtrapez_szog', NULL, 'Feladat');

		} elseif ($level <= 6) {

			$CI->load->library('12/Sikidom/Hurtrapez_terulet', NULL, 'Feladat');

		} else {

			$CI->load->library('12/Sikidom/Hurtrapez_koriv', NULL, 'Feladat');

		}

		$data = $CI->Feladat->Generate($level);

		return $data;
	}
}

?>