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

	// Define name and type of table columns
	public static $table_columns = array(
			'classes' => array(
				'name'	=> 'NOT NULL',
				'label'	=> 'NOT NULL'
				),
			'topics' => array(
				'classID'	=> 'FROM SESSION',
				'name'		=> 'NOT NULL',
				'label'		=> 'NOT NULL'
				),
			'subtopics' => array(
				'topicID'	=> 'FROM SESSION',
				'name'		=> 'NOT NULL',
				'label'		=> 'NOT NULL'
				),
			'quests' => array(
				'subtopicID'	=> 'FROM SESSION',
				'name'			=> 'NOT NULL',
				'label'			=> 'NOT NULL'
				),
			'exercises' => array(
				'questID'	=> 'FROM SESSION',
				'level' 	=> 9,
				'status' 	=> 'IN PROGRESS',
				'label'		=> '',
				'name'		=> '',
				'youtube'	=> '',
				'hint'		=> ''
				),
			'links' => array(
				'questID'	=> 'FROM SESSION',
				'label'		=> 'NOT NULL'
				)
			);

	/**
	 * Create tables
	 *
	 * @return void
	 */
	public function CreateTables() {

		$tables = SELF::$table_columns;
		foreach (array_keys($tables) as $table) {
			$this->CreateTable($table);
		}
	}

	/**
	 * Drop tables
	 *
	 * @return void
	 */
	public function DropTables() {

		$tables = array_reverse(SELF::$table_columns);
		foreach (array_keys($tables) as $table) {
			$this->DropTable($table);
		}
	}

	/**
	 * Drop table
	 *
	 * @param string $table Table name
	 *
	 * @return void
	 */
	public function DropTable($table) {

		if ($this->db->table_exists($table)) {
			if ($this->dbforge->drop_table($table)) {
				echo 'Table '.$table.' dropped!<br />';
			} else {
				show_error($this->db->_error_message());
			}
		} else {
			echo 'Table '.$table.' does not exist!<br />';
		}
	}

	/**
	 * Create table
	 *
	 * @param string $table Table name
	 *
	 * @return void
	 */
	public function CreateTable($table) {

		$sql = array(
			'classes' => 'CREATE TABLE classes (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							name 	VARCHAR(60) NOT NULL,
							label 	VARCHAR(30) NOT NULL
						)Engine=InnoDB;',
			'topics' => 'CREATE TABLE topics (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							classID INT NOT NULL,
							name 	VARCHAR(60) NOT NULL,
							label 	VARCHAR(30) NOT NULL,
							CONSTRAINT class_name UNIQUE (classID, name),
							FOREIGN KEY (classID) REFERENCES classes(id)
						)Engine=InnoDB;',
			'subtopics' => 'CREATE TABLE subtopics (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							topicID INT NOT NULL,
							name 	VARCHAR(60) NOT NULL,
							label 	VARCHAR(30) NOT NULL,
							CONSTRAINT topic_name UNIQUE (topicID, name),
							FOREIGN KEY (topicID) REFERENCES topics(id)
						)Engine=InnoDB;',
			'quests' => 'CREATE TABLE quests (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							subtopicID 	INT NOT NULL,
							label 		VARCHAR(30) NOT NULL UNIQUE,
							name 		VARCHAR(60) NOT NULL,
							FOREIGN KEY (subtopicID) REFERENCES subtopics(id)
						)Engine=InnoDB;',
			'exercises' => 'CREATE TABLE exercises (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							questID 	INT NOT NULL,
							level		INT,
							label 		VARCHAR(30),
							name 		VARCHAR(120),
							youtube 	VARCHAR(20),
							hint 		VARCHAR(60),
							status 		VARCHAR(20),
							FOREIGN KEY (questID) REFERENCES quests(id)
						)Engine=InnoDB;',
			'links' => 'CREATE TABLE links (
							id 		INT	NOT NULL AUTO_INCREMENT PRIMARY KEY,
							questID	INT,
							label 	VARCHAR(30) NOT NULL,
							CONSTRAINT link_label UNIQUE (questID, label),
							FOREIGN KEY (questID) REFERENCES quests(id),
							FOREIGN KEY (label) REFERENCES quests(label)
						)Engine=InnoDB;'
		);

		// Check table
		if (!isset($sql[$table])) {
			show_error('Table '.$table.' not defined!<br />');				
		}

		// Create table
		if (!$this->db->table_exists($table)) {

			if ($this->db->query($sql[$table])) {
				echo 'Table '.$table.' created!<br />';
			} else {
				show_error($this->db->_error_message());
			}
		} else {
			echo 'Table '.$table.' already exists!<br />';;
		}
	}

	/**
	 * Insert data
	 *
	 * Inserts data into database form json file when table name is defined.
	 * If column is missing from data and must not be null, throws an error.
	 * If it can be null, value is defined by an empty string ''.
	 *
	 * @param array  $data   Original data
	 * @param string $string Table name
	 *
	 * @return void
	 */
	public function InsertData($data, $table=NULL) {

		// print_r($table);
		if ($table) {
			// Check table
			if (NULL === SELF::$table_columns[$table]) {
				show_error('Table '.$table.' not defined!<br />');
			} else {

				// Get values
				foreach (SELF::$table_columns[$table] as $col => $type) {
					if (!isset($data[$col])) {

						// error: value missing!
						if ($type == 'NOT NULL') {
							print_r($data);
							show_error('Field '.$col.' is missing from data!');

						// insert value from session
						} elseif ($type == 'FROM SESSION') {
							$sessionID = str_split($col, 5)[0].'_ID';
							$values[$col] = $this->session->userdata($sessionID);

						// insert default value
						} elseif ($type != '') {
							$values[$col] = $type;
							
						// insert empty string
						} else {
							$values[$col] = '';
						}
						
					} else {
						$values[$col] = $data[$col];
					}
				}
			
				// Insert values
				if ($this->db->insert($table, $values)) {
					// echo 'Data inserted into '.$table.'!<br />';
				} else {
					show_error($this->db->_error_message());
				}
			}

			// Store ID
			$currentID = $this->db->insert_id();
			$sessionID = str_split($table, 5)[0].'_ID';
			$this->session->set_userdata($sessionID, $currentID);

			// Store exercises
			if ($table == 'exercises') {

				$names = $this->session->userdata('names');
				$exercises = $this->session->userdata('exercises');
				$exercises .= $names['classes']."\t"
						.$names['topics']."\t"
						.$names['subtopics']."\t"
						.$names['quests']."\t"
						.$data['name']."\t"
						.(isset($data['status']) && $data['status'] == 'OK' ? 'Kész' : 'Hiányos')."\r\n";
				$this->session->set_userdata('exercises', $exercises);

			} else {

				if (isset($data['name'])) {
					$names = $this->session->userdata('names');
					$names[$table] = $data['name'];
					$this->session->set_userdata('names', $names);
				}
			}
		}

		// Recursive check for other table names
		foreach (array_keys(SELF::$table_columns) as $column) {

			if (isset($data[$column])) {

				foreach ($data[$column] as $row) {

					$this->InsertData($row, $column);	
				}
			}
		}
	}

	/**
	 * Read file
	 *
	 * @param string $file File name
	 *
	 * @return array $data File content
	 */
	public function ReadFile($file) {

		if (file_exists($file)) {

			$json = file_get_contents($file);
			$data = json_decode($json, TRUE);

			return $data;

		} else {

			show_error('File '.$file.' not found!<br />');
		}
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
				INNER JOIN `quests` ON `subtopics`.`id` = `quests`.`subtopicID`
				INNER JOIN `exercises` ON `quests`.`id` = `exercises`.`questID`
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
				INNER JOIN `quests` ON `subtopics`.`id` = `quests`.`subtopicID`
				INNER JOIN `exercises` ON `quests`.`id` = `exercises`.`questID`
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
				INNER JOIN `quests` ON `subtopics`.`id` = `quests`.`subtopicID`
				INNER JOIN `exercises` ON `quests`.`id` = `exercises`.`questID`
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
	 * Get quest data for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $data Quest data
	 */
	public function GetExerciseQuest($id) {

		$query = $this->db->query(
			'SELECT DISTINCT `quests`.`id`, `quests`.`name` FROM `quests`
				INNER JOIN `exercises` ON `quests`.`id` = `exercises`.`questID`
					WHERE `exercises`.`id` = '.$id);
		$data = $query->result_array()[0];

		return $data;
	}
}

?>