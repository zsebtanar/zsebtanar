<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hints extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->load->helper('url');
		$this->load->helper('language');
		defined('RESOURCES_URL') OR define('RESOURCES_URL', base_url('resources/exercises'));
	}

	/**
	 * Add hints to exercise (if there is none)
	 *
	 * @param int   $id   Exercise id
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (with hints)
	 */
	public function AddHints($id, $data) {

		$hints = [];
		if (isset($data['hints'])) {
			if (is_array($data['hints'])) {

				// Is there more page?
				$multipage = TRUE;
				foreach ($data['hints'] as $value) {
					if (!is_array($value)) {
						$multipage = FALSE;
					}
				}

				// Create multipage hints
				if ($multipage) {
					foreach ($data['hints'] as $page) {
						$page = $this->AddHintPage($page);
						$hints = array_merge($hints, $page);
						
					}
				} else {

					$page = $this->AddHintPage($data['hints']);
					$hints = array_merge($hints, $page);
					// print_r($hints);

				}

			} else {

				// Single hints
				$page = $this->AddHintPage($data['hints']);
				$hints = array_merge($hints, $page);

			}
		} else {

			// No hints
			$hints =  NULL;

		}

		$data['hints']		= $hints;
		$data['hints_all'] 	= count($hints);
		$data['hints_used'] = 0;

		return $data;
	}

	/**
	 * Add page to hints
	 *
	 * @param array $page Hints data
	 *
	 * @return array $page_new Hints data (restructured)
	 */
	public function AddHintPage($page) {

		// Details
		foreach ($page as $key1 => $segment) {
			if (is_array($segment)) {
				$details = $this->AddHintDetails($segment);
				if ($key1 > 0) {
					$page[$key1-1] .= '<div><button class="pull-right btn btn-default btn-details" data-toggle="collapse" data-target="#hint_details'.$key1.'">'
						.'Részletek</button></div><br/>'
						.'<div id="hint_details'.$key1.'" class="collapse well well-sm small">'.$details.'</div>';
				} else {
					print_r('Az útmutató szerkezete hibás!');
				}
				unset($page[$key1]);
			}
		}

		// Restructure
		array_values($page);
		for ($i=0; $i < count($page); $i++) { 
			$hint = '';
			for ($j=0; $j <= $i; $j++) { 
				$hint .= '<p>'.strval($page[$j]).'</p>';
			}
			$page_new[] = $hint;
		}

		return $page_new;
	}

	/**
	 * Add details to hints
	 *
	 * @param array $subsegment Hints data
	 *
	 * @return string $details Hints data (modified)
	 */
	public function AddHintDetails($subsegment) {

		$details = '';
		foreach ($subsegment as $subsubsegment) {
			if (is_array($subsubsegment)) {
				print_r('Hiba az útmutatóban!');
				break;
			}
			$details .= '<p>'.strval($subsubsegment).'</p>';
		}

		return $details;
	}
}

?>