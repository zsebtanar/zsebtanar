<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Series_mixed {

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

		if ($level <= 3) {

			if (rand(1,2) == 1) {

				$CI->load->library('Series_arithmetic_member');
				$data = $CI->series_arithmetic_member->Generate($level);
			
			} else {

				$CI->load->library('Series_arithmetic_difference');
				$data = $CI->series_arithmetic_difference->Generate($level);

			}
			
		} elseif ($level <= 6) {

			if (rand(1,2) == 1) {

				$CI->load->library('Series_geometric_member');
				$data = $CI->series_geometric_member->Generate($level);

			} else {

				$CI->load->library('Series_geometric_ratio');
				$data = $CI->series_geometric_ratio->Generate($level);

			}

		} else {
			
			$CI->load->library('Median_mean');
			$data = $CI->median_mean->Generate($level);

		}

		return $data;
	}
}

?>