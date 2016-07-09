<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Abszolutertek_fuggveny {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('8/Fuggvenyabrazolas/Abszolutertek', NULL, 'Abszolutertek');
		$data = $CI->Abszolutertek->Generate($level);

		return $data;
	}
}

?>