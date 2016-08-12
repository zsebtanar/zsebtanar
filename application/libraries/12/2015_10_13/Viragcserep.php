<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viragcserep {

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

		if ($level <= 4) {

			$CI->load->library('12/Felszin/Csonkagula', NULL, 'Csonkagula');
			$data = $CI->Csonkagula->Generate($level);

		} else {

			$CI->load->library('11/Valoszinuseg/Kerteszet', NULL, 'Kerteszet');
			$data = $CI->Kerteszet->Generate($level);

		}

		return $data;
	}
}

?>