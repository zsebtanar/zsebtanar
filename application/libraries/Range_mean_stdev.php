<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Range_mean_stdev {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define range, mean, standard deviation
	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 3) {

			$CI->load->library('Range');
			$data = $CI->range->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('Mean');
			$data = $CI->mean->Generate($level);

		} else {

			$CI->load->library('Stdev');
			$data = $CI->stdev->Generate($level);

		}

		return $data;
	}
}

?>