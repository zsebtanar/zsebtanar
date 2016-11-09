<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->dbforge();
	}

	// Define name and type of table columns
	public function GetTableColumns() {
		return array(

			// Static tables for exercises
			'classes' => array(
				'label'	=> 'NOT NULL',
				'name'	=> 'NOT NULL'
				),
			'topics' => array(
				'classID'	=> 'FROM SESSION',
				'name'		=> 'NOT NULL'
				),
			'subtopics' => array(
				'topicID'	=> 'FROM SESSION',
				'name'		=> 'NOT NULL',
				'label'		=> 'NOT NULL'
				),
			'exercises' => array(
				'subtopicID'=> 'FROM SESSION',
				'level' 	=> 3,
				'label'		=> 'NOT NULL',
				'name'		=> 'NOT NULL',
				'difficulty'=> 3,
				'ex_order'	=> '',
				'link'		=> ''
				),
			'dependencies' => array(
				'exerciseID'	=> 'FROM SESSION',
				'classLabel'	=> 'NOT NULL',
				'subtopicLabel'	=> 'NOT NULL',
				'exerciseLabel'	=> 'NOT NULL'
				),
			'tags' 				=> [],
			'exercises_tags' 	=> [],
			);
	}

	/**
	 * Create tables
	 *
	 * @return void
	 */
	public function CreateTables() {

		$tables = $this->GetTableColumns();
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

		$tables = array_reverse($this->GetTableColumns());
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
							label 	VARCHAR(60) NOT NULL,
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
							label 	VARCHAR(60) NOT NULL,
							CONSTRAINT subtopic_name UNIQUE (topicID, label),
							FOREIGN KEY (topicID) REFERENCES topics(id)
						)Engine=InnoDB;',
			'exercises' => 'CREATE TABLE exercises (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							subtopicID 	INT NOT NULL,
							level		INT NOT NULL,
							label 		VARCHAR(60) NOT NULL,
							name 		VARCHAR(120),
							ex_order	VARCHAR(30),
							difficulty	INT,
							link		VARCHAR(120),
							FOREIGN KEY (subtopicID) REFERENCES subtopics(id)
						)Engine=InnoDB;',
			'tags' => 'CREATE TABLE tags (
							id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							label 	VARCHAR(60) NOT NULL,
							name 	VARCHAR(60) NOT NULL
						)Engine=InnoDB;',
			'exercises_tags' => 'CREATE TABLE exercises_tags (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							exerciseID 	INT NOT NULL,
							tagID 		INT NOT NULL,
							FOREIGN KEY (exerciseID) REFERENCES exercises(id),
							FOREIGN KEY (tagID) REFERENCES tags(id)
						)Engine=InnoDB;',
			'dependencies' => 'CREATE TABLE dependencies (
							id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							exerciseID 		INT NOT NULL,
							classLabel 		VARCHAR(60) NOT NULL,
							subtopicLabel 	VARCHAR(60) NOT NULL,
							exerciseLabel 	VARCHAR(60) NOT NULL,
							FOREIGN KEY (exerciseID) REFERENCES exercises(id)
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
	 * @param array  $data   Original data
	 * @param string $string Table name
	 *
	 * @return void
	 */
	public function InsertData($data, $table=NULL) {

		// print_r($table);
		if ($table !== NULL) {

			// Check table
			if (NULL === $this->GetTableColumns()[$table]) {
				show_error('Table '.$table.' not defined!<br />');
			} else {

				// Get values
				foreach ($this->GetTableColumns()[$table] as $col => $type) {
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

				// Insert tags separately
				if (isset($data['tags'])) {

					$exerciseID = $this->db->insert_id();
					$this->InsertTags($data['tags'], $exerciseID);
				}
			}

			// Store ID in session
			$currentID = $this->db->insert_id();
			$sessionID = str_split($table, 5)[0].'_ID';
			$this->session->set_userdata($sessionID, $currentID);

		}

		// Recursive check for other table names
		foreach (array_keys($this->GetTableColumns()) as $column) {

			if (isset($data[$column]) &&
				$column != 'tags' &&
				$column != 'exercises_tags' &&
				$column != 'link') {

				foreach ($data[$column] as $row) {

					$this->InsertData($row, $column);	
				}
			}
		}
	}

	/**
	 * Insert tags to database
	 *
	 * @param string $tags       String containing tags
	 * @param int    $exerciseID Exercise ID
	 *
	 * @return void
	 */
	public function InsertTags($tags, $exerciseID) {

		// Separate tags with comma or semicolon
		$tags = preg_split("/[,;]+/", $tags);

		foreach ($tags as $tag) {

			
			$query = $this->db->get_where('tags', ['name' => $tag]);
			$result = $query->result_array();

			// Check if tag exist in database
			if (count($result) > 0) {

				$tagID = $result[0]['id'];

			} else {

				// Insert new tag into database
				$label = slugify($tag);
				if ($this->db->insert('tags', ['name' => $tag, 'label' => $label])) {
					// echo 'Data inserted into tags!<br />';
				} else {
					show_error($this->db->_error_message());
				}

				$tagID = $this->db->insert_id();
			}

			// Insert relationship between exercise & tag
			if ($this->db->insert('exercises_tags', array(
				'exerciseID' 	=> $exerciseID,
				'tagID' 		=> $tagID
				))) {
				// echo 'Data inserted into exercises_tags!<br />';
			} else {
				show_error($this->db->_error_message());
			}
		}

		return;
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
}

?>