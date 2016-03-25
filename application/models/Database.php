<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->load->helper('url');
		$this->load->dbforge();
	}

	/**
	 * Check status for subtopic
	 *
	 * Status is 'OK' if there is >=1 exercise with 'OK' status and 'IN_PROGRESS' otherwise.
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $status Status
	 */
	public function SubtopicStatus($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `exercises`.`id` FROM `exercises`
					WHERE `exercises`.`status` = \'OK\'
					AND `exercises`.`subtopicID` = '.$id);
		$data = $query->result_array();
		$status = (count($data) > 0 ? 'OK' : 'IN_PROGRESS');

		return $status;
	}

	/**
	 * Check whether exercise exists
	 *
	 * @param string $label Exercise label
	 *
	 * @return bool $exists Whether exercise exists
	 */
	public function ExerciseExists($label) {

		$query = $this->db->get_where('exercises', array('label' => $label));

		$exists = count($query->result()) == 1;

 		return $exists;
	}

	/**
	 * Gets id for exercise
	 *
	 * @param int $label Exercise label
	 *
	 * @return int $id Exercise ID
	 */
	public function ExerciseID($label) {

		$query = $this->db->get_where('exercises', array('label' => $label));

		$id = $query->result()[0]->id;

 		return $id;
	}

	/**
	 * Gets id for subtopic
	 *
	 * @param int $label Subtopic label
	 *
	 * @return int $id Subtopic ID
	 */
	public function SubtopicID($label) {

		$query = $this->db->get_where('subtopics', array('label' => $label));

		$id = $query->result()[0]->id;

 		return $id;
	}

	/**
	 * Get maximum level for exercise
	 *
	 * $max_level shows how many times user needs to solve the exercise to complete it.
	 * If user is logged in, it is only 3 (for debugging purposes). 
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $max_level Maximum level
	 */
	public function getMaxLevel($id) {

		$query 	= $this->db->get_where('exercises', array('id' => $id));
		$max_level = $query->result()[0]->level;

 		return $max_level;
	}

	/**
	 * Get subtopic label for exercise
	 *
	 * @param int $label Exercise label
	 *
	 * @return int $subtopiclabel Subtopic label
	 */
	public function getSubtopicLabel($id, $type='exercise') {

		if ($type == 'exercise') {
			$query = $this->db->get_where('exercises', array('id' => $id));
			$subtopicID = $query->result()[0]->subtopicID;
			$query = $this->db->get_where('subtopics', array('id' => $subtopicID));
			$subtopiclabel = $query->result()[0]->label;
		} else {
			$query = $this->db->get_where('subtopics', array('id' => $id));
			$subtopiclabel = $query->result()[0]->label;
		}

 		return $subtopiclabel;
	}

	/**
	 * Get label for exercise
	 *
	 * Loads specific helper to access exercise function
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $exercise Exercise data
	 */
	public function ExerciseLabel($id) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0]; 

		return $exercise->label;
	}


	/**
	 * Get subtopic title
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $title Title
	 */
	public function SubtopicTitle($id) {

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));

		if (count($subtopics->result()) > 0) {

			$subtopic = $subtopics->result()[0];
			$title = $subtopic->name;

		} else {

			$title = 'Kezdőlap';

		}

		return $title;
	}

	/**
	 * Get link for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $href Link
	 */
	public function ExerciseLink($id) {

		$exercises = $this->db->get_where('exercises', array('id' => $id));

		if (count($exercises->result()) == 1) {


			$exercise = $exercises->result()[0];

			if ($this->Session->CheckLogin() || $exercise->status == 'OK') {

				$title = $exercise->name;
				$link = base_url().'view/exercise/'.$exercise->label;
				$name = $exercise->name;

			} else {

				$link = base_url().'view/main/';
				$name = 'Kezdőlap';

			}

		} else {

			$link = base_url().'view/main/';
			$name = 'Kezdőlap';

		}

		return array(
			'link' 	=> $link,
			'name' 	=> $name
			);
	}

	/**
	 * Get link for subtopic
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $link Link
	 */
	public function SubtopicLink($id) {

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));

		if (count($subtopics->result()) > 0 &&
			($this->Session->CheckLogin() || $this->Database->SubtopicStatus($id) == 'OK')) {

			$subtopic = $subtopics->result()[0];
			$link = base_url().'view/subtopic/'.$subtopic->label;
			$name = $subtopic->name;

		} else {

			$link = base_url().'view/main/';
			$name = 'Kezdőlap';

		}

		return array(
			'link' => $link,
			'name' => $name
		);
	}

	/**
	 * Get latest exercises
	 *
	 * @return array $data Latest exercises
	 */
	public function getLatest() {

		$query = $this->db->query(
			'SELECT DISTINCT `exercises`.`id`, `exercises`.`name`, `exercises`.`label` FROM `exercises`
					WHERE `exercises`.`status` = \'OK\'
					ORDER BY `exercises`.`finished` DESC');

		$data = $query->result_array();

		$length = min(10, count($data));
		$data = array_slice($data, 0, $length);

		return $data;
	}
}

?>