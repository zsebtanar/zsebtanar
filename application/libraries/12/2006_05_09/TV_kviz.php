<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TV_kviz {

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
		$CI->load->library('9/Szazalek/TV_jatek', NULL, 'TV_jatek');
		$data = $CI->TV_jatek->Generate($level);

		return $data;
	}
}

?>