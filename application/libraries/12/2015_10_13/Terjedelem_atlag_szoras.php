<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terjedelem_atlag_szoras {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define range, mean, standard deviation
	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 3) {

			$CI->load->library('9/Statisztika/Terjedelem', NULL, 'Terjedelem');
			$data = $CI->Terjedelem->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('9/Statisztika/Atlag', NULL, 'Atlag');
			$data = $CI->Atlag->Generate($level);

		} else {

			$CI->load->library('9/Statisztika/Szoras', NULL, 'Szoras');
			$data = $CI->Szoras->Generate($level);

		}

		return $data;
	}
}

?>