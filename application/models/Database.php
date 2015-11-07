<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {
		$this->load->dbutil();
		$this->load->dbforge();
	}

	/**
	 * Drop database if exists
	 *
	 * @param	string	$dbname Database name.
	 * @return	void
	 */
	public function Drop($dbname) {

		if ($this->dbutil->database_exists($dbname)) {
			if ($this->dbforge->drop_database($dbname)) {
				echo 'Database dropped!';
			} else {
				show_error($this->db->_error_message());
			}
		}		
	}

	/**
	 * Create database
	 *
	 * @param	string	$dbname Database name.
	 * @return	void
	 */
	public function Create($dbname) {

		if ($this->dbforge->create_database($dbname)) {
			echo 'Database created!';
		} else {
			show_error($this->db->_error_message());
		}

		return;
	}

	/**
	 * Connect to database
	 *
	 * @param	string	$dbname Database name.
	 * @return	void
	 */
	public function Connect($dbname) {

		if ($this->load->database($dbname)) {
			echo 'Connected to database!';
		} else {
			show_error($this->db->_error_message());
		}

		return;
	}
}

?>