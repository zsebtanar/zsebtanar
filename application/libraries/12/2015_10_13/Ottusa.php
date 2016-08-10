<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ottusa {

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

			$CI->load->library('5/Szoveges/Ottusa_pontok1', NULL, 'Ottusa');
			$data = $CI->Ottusa->Generate($level);

		} elseif ($level <= 4) {

			$CI->load->library('5/Szoveges/Ottusa_pontok2', NULL, 'Ottusa');
			$data = $CI->Ottusa->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('9/Szamhalmazok/Ottusa_1', NULL, 'Ottusa');
			$data = $CI->Ottusa->Generate($level);
			
		} elseif ($level <= 8) {

			$CI->load->library('9/Szamhalmazok/Ottusa_2', NULL, 'Ottusa');
			$data = $CI->Ottusa->Generate($level);

		} else {

			$CI->load->library('11/Kombinatorika/Ottusa_lovaglas', NULL, 'Exercise');
			$data = $CI->Exercise->Generate($level);

		}

		return $data;
	}
}

?>