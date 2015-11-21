<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exercises extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		$this->load->helper('url');
		define('RESOURCES_URL', base_url('resources/exercises'));
	}

	/**
	 * Check answer
	 *
	 * @param  array $data Answer data
	 * @return void
	 */
	public function CheckAnswer($data) {

		print_r($data);
		switch ($data['type']) {
			case 'int':
				
				if ($data['correct'] == $data['answer']) {
					return TRUE;
				} else {
					return FALSE;
				}

				break;
			
			default:
				break;
		}
	}

	/* Count apples */
	public function count_apples() {

		$level = 2;

		if ($level == 1) {
			$num = rand(0,4);
		} elseif ($level == 2) {
			$num = rand(5,9);
		} elseif ($level == 3) {
			$num = rand(10,20);
		}

		$question = 'Hány darab alma van a fán?<div class="text-center"><img class="img-question" width="50%" src="'.RESOURCES_URL.'/count_apples/tree'.$num.'.png"></div>';
		$correct = $num;
		$options = '';
		$solution = '$'.$correct.'$';
		$type = 'int';

		return array(
			'question' => $question,
			'options' => $options,
			'correct' => $correct,
			'solution' => $solution,
			'type' => $type
		);
	}

	/* Count apples */
	public function parity($level) {

		return 'asdlfkjasdf';
	}

}

?>