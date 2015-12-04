<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		$this->load->helper('url');
		$this->load->dbforge();
	}

	// Define name and type of table columns
	const TABLE_COLUMNS = array(
			'classes' => array(
				'name'	=> 'NOT NULL'
				),
			'topics' => array(
				'classID'	=> 'FROM SESSION',
				'name'		=> 'NOT NULL'
				),
			'subtopics' => array(
				'topicID'	=> 'FROM SESSION',
				'name'		=> 'NOT NULL'
				),
			'exercises' => array(
				'subtopicID'	=> 'FROM SESSION',
				'level'			=> 3,
				'name'			=> '',
				'label'			=> 'NOT NULL',
				'youtube'		=> ''
				),
			'links' => array(
				'exerciseID'	=> 'FROM SESSION',
				'label'			=> 'NOT NULL'
				),
			'sessions'			=> '',
			'quests' 			=> '',
			'actions' 			=> ''
			);

	/**
	 * Create tables
	 *
	 * @return void
	 */
	public function CreateTables() {

		$tables = SELF::TABLE_COLUMNS;
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

		$tables = array_reverse(SELF::TABLE_COLUMNS);
		foreach (array_keys($tables) as $table) {
			$this->DropTable($table);
		}
	}

	/**
	 * Drop table
	 *
	 * @param  string $table Table name
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
	 * @param  string $table Table name
	 * @return void
	 */
	public function CreateTable($table) {

		$sql = array(
			'classes' => 'CREATE TABLE classes (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							name 	VARCHAR(60) NOT NULL
						)Engine=InnoDB;',
			'topics' => 'CREATE TABLE topics (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							classID INT NOT NULL,
							name 	VARCHAR(60) NOT NULL,
							CONSTRAINT class_name UNIQUE (classID, name),
							FOREIGN KEY (classID) REFERENCES classes(id)
						)Engine=InnoDB;',
			'subtopics' => 'CREATE TABLE subtopics (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							topicID INT NOT NULL,
							name 	VARCHAR(60) NOT NULL,
							CONSTRAINT topic_name UNIQUE (topicID, name),
							FOREIGN KEY (topicID) REFERENCES topics(id)
						)Engine=InnoDB;',
			'exercises' => 'CREATE TABLE exercises (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							subtopicID 	INT NOT NULL,
							level 		INT,
							label 		VARCHAR(30) NOT NULL UNIQUE,
							name 		VARCHAR(120),
							youtube 	VARCHAR(20),
							FOREIGN KEY (subtopicID) REFERENCES subtopics(id)
						)Engine=InnoDB;',
			'links' => 'CREATE TABLE links (
							id 			INT	NOT NULL AUTO_INCREMENT PRIMARY KEY,
							exerciseID	INT	NOT NULL,
							label 		VARCHAR(30) NOT NULL,
							CONSTRAINT link_label UNIQUE (id, label),
							FOREIGN KEY (exerciseID) REFERENCES exercises(id),
							FOREIGN KEY (label) REFERENCES exercises(label)
						)Engine=InnoDB;',
			'sessions' => 'CREATE TABLE sessions (
							id 			INT	NOT NULL AUTO_INCREMENT PRIMARY KEY
						)Engine=InnoDB;',
			'quests' => 'CREATE TABLE quests (
							id 			INT	NOT NULL AUTO_INCREMENT PRIMARY KEY,
							sessionID	INT	NOT NULL,
							method 		VARCHAR(30) NOT NULL,
							name 		VARCHAR(30) NOT NULL,
							status 		VARCHAR(30) NOT NULL,
							FOREIGN KEY (sessionID) REFERENCES sessions(id)
						)Engine=InnoDB;',
			'actions' => 'CREATE TABLE actions (
							id 			INT	NOT NULL AUTO_INCREMENT PRIMARY KEY,
							questID		INT	NOT NULL,
							time 		TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
							name 		VARCHAR(30) NOT NULL,
							progress 	DECIMAL(6,2),
							result 		VARCHAR(30),
							FOREIGN KEY (questID) REFERENCES quests(id)
						)Engine=InnoDB;',
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
	 * @param  array  $data   Original data
	 * @param  string $string Table name
	 * @return void
	 */
	public function InsertData($data, $table=NULL) {

		// print_r($table);
		if ($table) {
			// Check table
			if (NULL === SELF::TABLE_COLUMNS[$table]) {
				show_error('Table '.$table.' not defined!<br />');
			} else {

				// Get values
				foreach (SELF::TABLE_COLUMNS[$table] as $col => $type) {
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
					echo 'Data inserted into '.$table.'!<br />';
				} else {
					show_error($this->db->_error_message());
				}
			}

			// Store ID
			$currentID = $this->db->insert_id();
			$sessionID = str_split($table, 5)[0].'_ID';
			$this->session->set_userdata($sessionID, $currentID);
		}

		$this->recursiveInsert($data);
	}

	/**
	 * Insert data recoursively
	 *
	 * Calls insertData() function for each column of data.
	 *
	 * @param  array  $data   Original data
	 * @return void
	 */
	public function recursiveInsert($data) {

		// Recursive check for other table names
		foreach (array_keys(SELF::TABLE_COLUMNS) as $column) {

			if (isset($data[$column])) {

				foreach ($data[$column] as $row) {

					$this->InsertData($row, $column);	
				}
			}
		}
	}

	/**
	 * Unset user data
	 *
	 * Unsets unused session variables defined during inserting data.
	 *
	 * @return void
	 */
	public function UnsetUserData() {

		$this->session->unset_userdata('_ID');
		$this->session->unset_userdata('class_ID');
		$this->session->unset_userdata('topic_ID');
		$this->session->unset_userdata('subto_ID');
		$this->session->unset_userdata('exerc_ID');
		$this->session->unset_userdata('links_ID');

		return;
	}

	/**
	 * Read file
	 *
	 * @param  string $file File name
	 * @return array  $data File content
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
	 * Redirect page
	 *
	 * @param  int $id    Subtopic ID
	 * @param  int $level Excercise level
	 * @return void
	 */
	public function Redirect() {

		header('Location:'.base_url().'view/subtopic/');
	}

	/**
	 * Search
	 *
	 * @param  string $keyword Keyword
	 * @return string $output  Html output
	 */
	public function Search($keyword) {

		if ($keyword != '') {
			$this->db->like('name', $keyword, 'both');
			$this->db->order_by('label');
			$query = $this->db->get('exercises');
			$output = $query->result_array();
		} else {
			$output = [];
		}

		return $output;
	}
}

?>