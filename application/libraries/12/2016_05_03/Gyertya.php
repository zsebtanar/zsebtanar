<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyertya {

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

			$CI->load->library('12/Terfogat/Gula', NULL, 'Gyertya_Exercise');

		} elseif ($level <= 6) {

			$CI->load->library('11/Kombinatorika/Gula_szinezes', NULL, 'Gyertya_Exercise');

		} else {

			$CI->load->library('11/Kombinatorika/Varazskanoc', NULL, 'Gyertya_Exercise');

		}

		$data = $CI->Gyertya_Exercise->Generate($level);

		return $data;
	}
}

?>