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

			$CI->load->library('12/Sikidom/Hurtrapez_szog', NULL, 'Hurtrapez_Exercise');

		} elseif ($level <= 6) {

			$CI->load->library('12/Sikidom/Hurtrapez_terulet', NULL, 'Hurtrapez_Exercise');

		} else {

			$CI->load->library('12/Sikidom/Hurtrapez_koriv', NULL, 'Hurtrapez_Exercise');

		}

		$data = $CI->Hurtrapez_Exercise->Generate($level);

		return $data;
	}
}

?>