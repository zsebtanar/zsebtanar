<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haromszog_belso_szogei {

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

			$CI->load->library('10/Trigonometrikus_fuggvenyek/Haromszog_tangens', NULL, 'Haromszog');
			$data = $CI->Haromszog->Generate($level);

		} elseif ($level <= 6) {

			$type = rand(0,1);

			if ($type) {

				$CI->load->library('10/Trigonometrikus_fuggvenyek/Haromszog_koszinusz', NULL, 'Haromszog');
				$data = $CI->Haromszog->Generate($level);

			} else {

				$CI->load->library('10/Trigonometrikus_fuggvenyek/Haromszog_szinusz', NULL, 'Haromszog');
				$data = $CI->Haromszog->Generate($level);

			}

		} else {

				$CI->load->library('8/Sikgeometria/Pitagorasz', NULL, 'Haromszog');
				$data = $CI->Haromszog->Generate($level);

		}

		return $data;
	}
}

?>