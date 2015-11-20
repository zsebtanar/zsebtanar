<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_model {

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
				'name'			=> 'NOT NULL',
				'label'			=> '',
				'youtube'		=> ''
				),
			'links' => array(
				'exerciseID1'	=> 'NOT NULL',
				'exerciseID2'	=> 'NOT NULL'
				)
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

		$this->load->dbforge();

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
	 * Delete subtopic from tables
	 *
	 * @param  int $id Subtopic ID
	 * @return void
	 */
	public function DeleteFromTables($id) {

		$tables = array('exercises',);

		foreach ($tables as $table) {

			$this->db->where(array('subtopicID' => $id));

			if ($this->db->delete($table)) {
				echo 'Data deleted from '.$table.'!<br />';
			} else {
				show_error($this->db->_error_message());
			}
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
							id 		INT 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
							name 	VARCHAR(60) NOT NULL
						)Engine=InnoDB;',
			'topics' => 'CREATE TABLE topics (
							id 		INT 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
							classID INT 		NOT NULL,
							name 	VARCHAR(60) NOT NULL,
							CONSTRAINT class_name UNIQUE (classID, name),
							FOREIGN KEY (classID) REFERENCES classes(id)
						)Engine=InnoDB;',
			'subtopics' => 'CREATE TABLE subtopics (
							id 		INT 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
							topicID INT 		NOT NULL,
							name 	VARCHAR(60) NOT NULL,
							CONSTRAINT topic_name UNIQUE (topicID, name),
							FOREIGN KEY (topicID) REFERENCES topics(id)
						)Engine=InnoDB;',
			'exercises' => 'CREATE TABLE exercises (
							id 			INT 		NOT NULL AUTO_INCREMENT PRIMARY KEY,
							subtopicID 	INT 		NOT NULL,
							name 		VARCHAR(30) NOT NULL UNIQUE,
							label 		VARCHAR(120),
							youtube 	VARCHAR(20),
							FOREIGN KEY (subtopicID) REFERENCES subtopics(id)
						)Engine=InnoDB;',
			'links' => 'CREATE TABLE links (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							exerciseID1 INT NOT NULL,
							exerciseID2 INT NOT NULL,
							FOREIGN KEY (exerciseID1) REFERENCES exercises(id),
							FOREIGN KEY (exerciseID2) REFERENCES exercises(id)
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
	 * If column is missing from data and must not be null, throws an error.
	 * If it can be null, value is defined by an empty string ''.
	 *
	 * @param  array  $data   Original data
	 * @param  int    $id     Subtopic ID
	 * @param  string $string Table name
	 * @return void
	 */
	public function InsertData($data, $id=NULL, $table=NULL) {

		// print_r($table);
		if ($table) {
			// Check table
			if (NULL === SELF::TABLE_COLUMNS[$table]) {
				show_error('Table '.$table.' not defined!<br />');
			} else {

				// Get values
				foreach (SELF::TABLE_COLUMNS[$table] as $col => $type) {
					if (!isset($data[$col])) {
						if ($type == 'NOT NULL') {
							print_r($data);
							show_error('Field '.$col.' is missing from data!');
						} elseif ($type == 'FROM SESSION') {
							$sessionID = str_split($col, 5)[0].'_ID';
							$values[$col] = $this->session->userdata($sessionID);
						} else {
							$values[$col] = '';
						}
					} else {
						$values[$col] = $data[$col];
					}
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

		// Recursive check for other table names
		foreach (array_keys(SELF::TABLE_COLUMNS) as $column) {

			if (isset($data[$column])) {

				foreach ($data[$column] as $row) {

					if (!$id) {

						// Full update
						$this->InsertData($row, $id, $column);	
					} else {

						// Partial update
						if (in_array($column, array('classes','topics','subtopics'))) {
							$this->InsertData($row, $id);
						} elseif ($id == $this->session->userdata('subto_ID')) {
							$this->InsertData($row, $id, $column);
						}
					}
				}
			}
		}
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
	public function Redirect($id=NULL, $level=NULL) {

		$this->load->helper('url');
		header('Location:'.base_url().'page/view/'.$id.($id ? '/' : '').$level);
	}

	/**
	 * Search
	 *
	 * @param string $name String name
	 * @return void
	 */
	public function Search($name) {

		$this->db->select('exercises');
		$this->db->like('label', $name);
		$query = $this->db->get('exercises');

		if ($query->num_rows > 0) {
			foreach ($query->result_array() as $row){
				$row_set[] = htmlentities(stripslashes($row['label']));
			}
			echo json_encode($row_set);
		}
	}
}

?>