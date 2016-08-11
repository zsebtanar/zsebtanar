<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tigris {

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

			$CI->load->library('11/Hatvany/Tigris1', NULL, 'Tigris1');
			$data = $CI->Tigris1->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('11/Hatvany/Tigris2', NULL, 'Tigris2');
			$data = $CI->Tigris2->Generate($level);

		} else {

			$CI->load->library('11/Kombinatorika/Tigris_tenyeszt', NULL, 'Tigris');
			$data = $CI->Tigris->Generate($level);

		}

		return $data;
	}
}

?>