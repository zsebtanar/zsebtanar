<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kulonbozo_szamjegyek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('11/Kombinatorika/Szamjegyek', NULL, 'Szamjegyek');
		$data = $CI->Szamjegyek->Generate($level);

		return $data;
	}
}

?>