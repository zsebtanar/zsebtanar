<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

    function Generate($level) {

        $question = 'What is a square?';
        $options = array('rectangle', 'parallelogram', 'circle');
        $correct = array(1, 1, 0);
        $solution = 'The square is a rectangle and a parallelogram but not a circle.';

        return array(
            'question'  => $question,
            'options'   => $options,
            'correct'   => $correct,
            'solution'  => $solution,
            'type'		=> 'multi'
        );
    }
}

?>