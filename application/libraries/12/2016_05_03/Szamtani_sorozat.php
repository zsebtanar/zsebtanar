<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szamtani_sorozat {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define member of arithmetic & geometric series
	function Generate($level) {

		$CI =& get_instance();

		$CI->load->library('12/Szamtani_sorozat/Tag1', NULL, 'Szamtani_tag');
		$data = $CI->Szamtani_tag->Generate($level);

		return $data;
	}
}

?>