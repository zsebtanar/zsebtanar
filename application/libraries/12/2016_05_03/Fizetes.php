<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fizetes {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 4) {

			$CI->load->library('12/Mertani_sorozat/Osszeg', NULL, 'Osszeg');
			$data = $CI->Osszeg->Generate($level);

		} else {

			$CI->load->library('9/Statisztika/Atlag_munkaora', NULL, 'Atlag_munkaora');
			$data = $CI->Atlag_munkaora->Generate($level);

		}

		return $data;
	}
}

?>