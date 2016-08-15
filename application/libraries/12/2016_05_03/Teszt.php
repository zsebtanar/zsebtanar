<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teszt {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$CI =& get_instance();

		$level = 9;

		if ($level <= 2) {

			$CI->load->library('6/Statisztika/Teszt_kordiagram', NULL, 'Teszt_Exercise');

		} elseif ($level <= 4) {

			$CI->load->library('7/Grafikon/Teszt_grafikon', NULL, 'Teszt_Exercise');

		} elseif ($level <= 7) {

			$CI->load->library('9/Halmazmuveletek/Teszt_diagram', NULL, 'Teszt_Exercise');

		} else {

			$CI->load->library('11/Valoszinuseg/Teszt_pontok', NULL, 'Teszt_Exercise');

		}

		$data = $CI->Teszt_Exercise->Generate($level);

		return $data;
	}
}

?>