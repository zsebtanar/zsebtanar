<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenes_forditott_aranyossag {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define type of proportionality
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('9/Aranyossag/Egyenes_forditott', NULL, 'Egyenes_forditott');
		$data = $CI->Egyenes_forditott->Generate($level);

		return $data;
	}
}

?>