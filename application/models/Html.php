<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_model {

	/**
	 * Print navbar menu
	 *
	 * @return string $html Html-code
	 */
	public function printNavBarMenu() {

		$this->load->helper('url');

		$classes = $this->db->get('classes');
		$html = '';

		foreach ($classes->result() as $class) {

			$topics = $this->db->get_where('topics', array('classID' => $class->id));

			foreach ($topics->result() as $topic) {

				$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));

				foreach ($subtopics->result() as $subtopic) {
					echo base_url();

				}
			}
		}
	}
}

?>