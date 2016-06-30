<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szazalek_szalami {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Get value of VAT of a pair of jeans
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('9/Szazalek/Szalami', NULL, 'Szalami');
		$data = $CI->Szalami->Generate($level);

		return $data;
	}
}

?>