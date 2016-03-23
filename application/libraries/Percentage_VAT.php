<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Count_1_20 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Get value of VAT of an article
	function Percentage_VAT($level) {

		$num = rand(max(0,2*($level-2)), min(20,3*$level));

		$question = 'A ruházati cikkek nettó árát 27%-kal növeli meg az áfa (általános forgalmi adó). A nettó
ár és az áfa összege a bruttó ár, amelyet a vásárló fizet a termék vásárlásakor. Egy nadrágért 6350 Ft-ot fizetünk.
Hány forint áfát tartalmaz a nadrág ára? Megoldását részletezze!';
		$correct = $num;
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution
		);
	}
}

?>