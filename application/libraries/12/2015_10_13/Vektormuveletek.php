<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vektormuveletek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 3) {

			$CI->load->library('9/Vektor/Osszeadas', NULL, 'Osszeadas');
			$data = $CI->Osszeadas->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('9/Vektor/Kivonas', NULL, 'Kivonas');
			$data = $CI->Kivonas->Generate($level);

		} else {

			$CI->load->library('9/Sikgeometria/Rombusz', NULL, 'Rombusz');
			$data = $CI->Rombusz->Generate($level);

		}

		return $data;
	}
}
?>