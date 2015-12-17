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
	public static $table_columns = array(
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
				'level'			=> 1,
				'name'			=> '',
				'label'			=> 'NOT NULL',
				'youtube'		=> '',
				'download'		=> ''
				),
			'quizzes' => array(
				'exerciseID'	=> 'FROM SESSION',
				'question'		=> 'NOT NULL',
				'correct'		=> 'NOT NULL',
				'wrong1'		=> 'NOT NULL',
				'wrong2'		=> 'NOT NULL'
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
							download 	VARCHAR(60),
							FOREIGN KEY (subtopicID) REFERENCES subtopics(id)
						)Engine=InnoDB;',
			'quizzes' => 'CREATE TABLE quizzes (
							id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							exerciseID 	INT NOT NULL UNIQUE,
							question 	VARCHAR(240) NOT NULL,
							correct 	VARCHAR(120) NOT NULL,
							wrong1 		VARCHAR(120) NOT NULL,
							wrong2 		VARCHAR(120) NOT NULL,
							FOREIGN KEY (exerciseID) REFERENCES exercises(id)
						)Engine=InnoDB;',
			'links' => 'CREATE TABLE links (
							id 			INT	NOT NULL AUTO_INCREMENT PRIMARY KEY,
							exerciseID	INT,
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
							time 		TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
							class 		VARCHAR(120) NOT NULL,
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
							level 		INT,
							level_max 	INT,
							result 		VARCHAR(30),
							todo 		INT,
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
	 * @param  int $id	Subtopic ID
	 * @param  int $level Excercise level
	 * @return void
	 */
	public function Redirect() {

		header('Location:'.base_url().'view/main/');
	}

	/**
	 * Search
	 *
	 * @param string $keyword Keyword
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return string $output  Html output
	 */
	public function Search($keyword, $classID=NULL, $topicID=NULL) {

		if ($keyword != '') {
			if ($classID) {
				if ($topicID) {
					$query = $this->db->query(
						'SELECT `exercises`.`name`, `exercises`.`id` FROM `exercises`
							INNER JOIN `subtopics` ON `subtopics`.`id` = `exercises`.`subtopicID`
							WHERE `exercises`.`name` LIKE \'%'.$keyword.'%\' ESCAPE \'!\'
							AND `subtopics`.`topicID` = '.$topicID.
							' ORDER BY `exercises`.`name`');
				} else {
					$query = $this->db->query(
						'SELECT `exercises`.`name`, `exercises`.`id` FROM `exercises`
							INNER JOIN `subtopics` ON `subtopics`.`id` = `exercises`.`subtopicID`
							INNER JOIN `topics` ON `topics`.`id` = `subtopics`.`topicID`
							WHERE `exercises`.`name` LIKE \'%'.$keyword.'%\' ESCAPE \'!\'
							AND `topics`.`classID` = '.$classID.
							' ORDER BY `exercises`.`name`');
				}
			} else {
				$this->db->like('name', $keyword, 'both');
				$this->db->order_by('name');
				$query = $this->db->get('exercises');
			}
			$output = $query->result_array();
			// print_r($this->db->last_query());
		} else {
			$output = [];
		}

		return $output;
	}

	/**
	 * Get class name
	 *
	 * Searches for class name of exercise/subtopic.
	 *
	 * @param  int	$id   ID
	 * @param  string $type Id type (exercise/subtopic)
	 *
	 * @return string $class Class name
	 */
	public function GetClassName($id, $type) {

		if ($type == 'exercise') {

			$query = $this->db->query(
				'SELECT DISTINCT `classes`.`name` as `class` FROM `classes`
					INNER JOIN `topics` ON `classes`.`id` = `topics`.`classID`
					INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
					INNER JOIN `exercises` ON `subtopics`.`id` = `exercises`.`subtopicID`
						WHERE `exercises`.`id` = '.$id);
			$class = $query->result()[0]->class;

		} elseif ($type == 'subtopic') {

			$query = $this->db->query(
				'SELECT DISTINCT `classes`.`name` as `class` FROM `classes`
					INNER JOIN `topics` ON `classes`.`id` = `topics`.`classID`
					INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
						WHERE `subtopics`.`id` = '.$id);
			$class = $query->result()[0]->class;

		}

		return $class;
	}

	/**
	 * Get search data
	 *
	 * Gets all classes + topics if class is defined
	 *
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return array $data Class data
	 */
	public function getSearchData($classID=NULL, $topicID=NULL) {

		$query = $this->db->query('SELECT * FROM `classes`');
		$classes = $query->result_array();
		array_unshift($classes, array('id' => NULL, 'name' => 'Mindenhol'));

		if ($classID) {
			$query = $this->db->query('SELECT `name` FROM `classes` WHERE `id` = '.$classID);
			$className = $query->result()[0]->name;

			$query = $this->db->query('SELECT * FROM `topics` WHERE `classID` = '.$classID);
			$topics = $query->result_array();
			array_unshift($topics, array('id' => NULL, 'name' => 'Mindenhol'));

			if ($topicID) {
				$query = $this->db->query('SELECT `name` FROM `topics` WHERE `id` = '.$topicID);
				$topicName = $query->result()[0]->name;
			} else {
				$topicName = 'Válassz témakört!';
			}
		} else {
			$topics = NULL;
			$topicName = NULL;
			$className = 'Válassz osztályt!';
		}

		return array(
			'classes' 	=> $classes,
			'className' => $className,
			'classID' 	=> $classID,
			'topics' 	=> $topics,
			'topicName' => $topicName,
			'topicID' 	=> $topicID
		);
	}

	/**
	 * Get topic name
	 *
	 * Searches for topic name of exercise/subtopic.
	 *
	 * @param  int	$id   ID
	 * @param  string $type Id type (exercise/subtopic)
	 *
	 * @return string $topic Topic name
	 */
	public function GetTopicName($id, $type) {

		if ($type == 'exercise') {

			$query = $this->db->query(
				'SELECT DISTINCT `topics`.`name` as `topic` FROM `topics`
					INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
					INNER JOIN `exercises` ON `subtopics`.`id` = `exercises`.`subtopicID`
						WHERE `exercises`.`id` = '.$id);
			$topic = $query->result()[0]->topic;

		} elseif ($type == 'subtopic') {

			$query = $this->db->query(
				'SELECT DISTINCT `topics`.`name` as `topic` FROM `topics`
					INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
						WHERE `subtopics`.`id` = '.$id);
			$topic = $query->result()[0]->topic;

		}

		return $topic;
	}

	/**
	 * Get quiz data
	 *
	 * Checks whether exercise is quiz or not. If so, returns quiz data, else returns
	 * false.
	 *
	 * @param int $id Exercise id
	 *
	 * @return array $data Quiz data
	 */
	public function getQuizData($id) {

		$query = $this->db->query('SELECT * FROM `quizzes` WHERE `quizzes`.`exerciseID` = '.$id);

		if ($query->num_rows() > 0) {

			$data = $query->result_array()[0];
			return $data;

		} else {

			return FALSE;
		}
	}

	/**
	 * Record action
	 *
	 * Data is recorded when user attempts to solve an exercise.
	 *
	 * @param  int 	  $id	 Subtopic/Exercise ID
	 * @param  int	$level  Exercise level
	 * @param  string $result Result (correct/wrong/not_done)
	 *
	 * @return void
	 */
	public function recordAction($id, $level=NULL, $result=NULL) {

		$query 		= $this->db->get_where('exercises', array('id' => $id));
		$exercise 	= $query->result()[0];
		$name 		= $exercise->name;
		$level_max	= $exercise->level;

		$data['questID'] 	= $this->session->userdata('questID');
		$data['level']		= $level;
		$data['level_max']	= $level_max;
		$data['result']		= $result;
		$data['name'] 		= $name;

		$todo_length		= count($this->session->userdata('todo_list'));
		$data['todo'] 		= $todo_length;

		if (!$this->db->insert('actions', $data)) {
			show_error($this->db->_error_message());
		}

		return;
	}

	/**
	 * Convert string to Ascii
	 *
	 * @param string $str Original string.
	 *
	 * @return string $clean Modified string.
	 */
	public function toAscii($str)
	{
		$change = array('Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ö'=>'O',
						'Ő'=>'O', 'Ú'=>'U', 'Ü'=>'U', 'Ű'=>'U',
						'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ö'=>'o',
						'ő'=>'o', 'ú'=>'u', 'ü'=>'u', 'ű'=>'u', '.'=>'');
		$clean = strtr($str, $change);
		$clean = preg_replace("/[\/|+ -]+/", '_', $clean);
		$clean = preg_replace("/[^a-zA-Z0-9_-]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));

		return $clean;
	}
}

?>