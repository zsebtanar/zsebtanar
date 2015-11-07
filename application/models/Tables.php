<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->load->dbforge();
	}

	/**
	 * Create table
	 *
	 * @param  string $name Table name
	 * @return void
	 */
	public function Create($name) {

		switch ($name) {
			case 'classes':
				
				$sql = 	'CREATE TABLE classes (
							id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
							alt VARCHAR(60) NOT NULL UNIQUE,
							name VARCHAR(60) NOT NULL,
							PRIMARY KEY (id)
						);';
				break;
			
			default:

				show_error('Table '.$name.' not defined!');
				return FALSE;				
		}

		if (!$this->db->table_exists($name)) {

			if ($this->db->query($sql)) {
				echo 'Table '.$name.' created!';
			} else {
				show_error($this->db->_error_message());
			}
		} else {
			echo 'Table '.$name.' already exists!';;
		}
	}

	/**
	 * Drop table
	 *
	 * @param  string $name Table name
	 * @return void
	 */
	public function Drop($name) {

		if ($this->db->table_exists($name)) {
			if (!$this->dbforge->drop_table($name)) {
				echo 'Table '.$name.' dropped!';
			} else {
				show_error($this->db->_error_message());
			}
		} else {
			echo 'Table '.$name.' does not exist!';
		}
	}
}

?>