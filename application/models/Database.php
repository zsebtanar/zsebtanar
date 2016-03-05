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
	 * Get class label
	 *
	 * Searches for class label of exercise (to access math functions).
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $label Class label
	 */
	public function GetClassLabel($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `classes`.`label` FROM `classes`
				INNER JOIN `topics` ON `classes`.`id` = `topics`.`classID`
				INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
				INNER JOIN `exercises` ON `subtopics`.`id` = `exercises`.`subtopicID`
					WHERE `exercises`.`id` = '.$id);
		$label = $query->result()[0]->label;

		return $label;
	}

	/**
	 * Get topic label
	 *
	 * Searches for topic label of exercise (to access math functions).
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $label Topic label
	 */
	public function GetTopicLabel($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `topics`.`label` FROM `topics`
				INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
				INNER JOIN `exercises` ON `subtopics`.`id` = `exercises`.`subtopicID`
					WHERE `exercises`.`id` = '.$id);
		$label = $query->result()[0]->label;

		return $label;
	}

	/**
	 * Get subtopic label
	 *
	 * Searches for topic label of exercise (to access math functions).
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $label Topic label
	 */
	public function GetSubtopicLabel($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `subtopics`.`label` FROM `subtopics`
				INNER JOIN `exercises` ON `subtopics`.`id` = `exercises`.`subtopicID`
					WHERE `exercises`.`id` = '.$id);
		$label = $query->result()[0]->label;

		return $label;
	}

	/**
	 * Get class data for subtopic
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return array $data Class data
	 */
	public function GetSubtopicClass($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `classes`.`id`, `classes`.`name` FROM `classes`
				INNER JOIN `topics` ON `classes`.`id` = `topics`.`classID`
				INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
					WHERE `subtopics`.`id` = '.$id);
		$data = $query->result_array()[0];

		return $data;
	}

	/**
	 * Get topic data for subtopic
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return array $data Topic data
	 */
	public function GetSubtopicTopic($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `topics`.`id`, `topics`.`name` FROM `topics`
				INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
					WHERE `subtopics`.`id` = '.$id);
		$data = $query->result_array()[0];

		return $data;
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
	 * @param int $id Exercise ID
	 *
	 * @return bool $exists Whether exercise exists
	 */
	public function ExerciseExists($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));

		$exists = count($query->result()) == 1;

 		return $exists;
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
	 * Get subtopic ID for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $subtopicID Subtopic ID
	 */
	public function getSubtopicID($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$subtopicID = $query->result()[0]->subtopicID;

 		return $subtopicID;
	}
}

?>