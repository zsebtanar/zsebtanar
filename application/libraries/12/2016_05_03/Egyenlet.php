<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlet {

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

			$CI->load->library('9/Egyenletek/Tortek', NULL, 'Egyenlet');

		} else {

			$CI->load->library('10/Masodfoku_egyenlet/Egyenlotlenseg_grafikus', NULL, 'Egyenlet');
			
		}
		
		$data = $CI->Egyenlet->Generate($level);

		return $data;
	}
}

?>