<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_model {

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

	/**
	 * Add user
	 *
	 * Add new user in the users table
	 *
	 * @return void
	 */
	public function AddUser() {

		if (!$this->session->userdata('userID') &&
			$this->db->table_exists('users')) {

			$this->db->insert('users', ['id' => NULL]);
			$id = $this->db->insert_id();
			$this->session->set_userdata('userID', $id);

		}

		return;
	}

	/**
	 * Add user exercise
	 *
	 * Add exercise to user activity list
	 *
	 * @param int    $exerciseID Exercise ID
	 * @param string $access     How was this exercise accessed by user?
	 *
	 * @return void
	 **/
	public function AddUserExercise($exerciseID, $access) {

		$userID = $this->session->userdata('userID');

		if (!$this->session->userdata('user_exerciseID') || $access) {

			$this->db->insert('user_exercises', array(
					'exerciseID' 	=> $exerciseID,
					'userID' 		=> $userID,
					'access' 		=> $access
				)
			);

			$id = $this->db->insert_id();
			$this->session->set_userdata('user_exerciseID', $id);

		}

		return;
	}

	/**
	 * Add user action
	 *
	 * Add action to user activity list
	 *
	 * @param int    $level      Exercise level
	 * @param int    $hints_used Used hints
	 * @param string $status     Status
	 *
	 * @return void
	 **/
	public function AddUserAction($level, $hints_used, $hints_all, $status) {

		$userID = $this->session->userdata('userID');
		$user_exerciseID = $this->session->userdata('user_exerciseID');

		$this->db->insert('user_actions', array(
				'result' 			=> $status,
				'usedHints' 		=> $hints_used,
				'allHints'	 		=> $hints_all,
				'user_exerciseID' 	=> $user_exerciseID,
				'userID' 			=> $userID,
				'level' 			=> $level
			)
		);

		return;
	}

	/**
	 * User session start
	 *
	 * Define when user started session
	 *
	 * @param int $userID User ID
	 *
	 * @return string $time_start time
	 **/
	public function UserSessionStart($userID) {

		$user = $this->db->get_where('users', array('id' => $userID));

		$time_start = $user->result()[0]->start;

		return $time_start;
	}

	/**
	 * User duration
	 *
	 * Define how much time user spent on website
	 *
	 * @param int $userID User ID
	 *
	 * @return string $duration Time
	 **/
	public function UserDuration($userID) {

		$user = $this->db->get_where('users', array('id' => $userID));

		$time_start = date_create($user->result()[0]->start);

		$this->db->order_by('id', 'desc');

		$actions = $this->db->get_where('user_actions', array('userID' => $userID));

		if (count($actions->result()) > 0) {

			$time_end = date_create($actions->result()[0]->time);

		} else {

			$time_end = $time_start;

		}

		$time_diff = date_diff($time_start, $time_end);

		if ($time_end > $time_start) {
			$duration = $this->FormatTime($time_diff);
		} else {
			$duration = NULL;
		}

		return $duration;
	}

	/**
	 * User exercises
	 *
	 * Define how many exercises user visited
	 *
	 * @param int $userID User ID
	 *
	 * @return int $user_exercises No. of exercises
	 **/
	public function UserExercises($userID) {

		$user = $this->db->get_where('users', array('id' => $userID));

		$user_exercises = $this->db->get_where('user_exercises', array('userID' => $userID));

		return $user_exercises->result_array();
	}

	/**
	 * User max level
	 *
	 * Define maximum level of exercises solved by user
	 *
	 * @param int $userID User ID
	 *
	 * @return int $max_level Maximum level
	 **/
	public function UserMaxLevel($userID) {

		$max_level = 1;

		$user = $this->db->get_where('users', array('id' => $userID));

		$user_exercises = $this->db->get_where('user_exercises', array('userID' => $userID));

		if (count($user_exercises->result()) > 0) {

			foreach ($user_exercises->result() as $user_exercise) {

				$actions = $this->db->get_where('user_actions', array('user_exerciseID' => $user_exercise->id));

				if (count($actions->result()) > 0) {

					foreach ($actions->result() as $action) {
						
						$max_level = max($action->level, $max_level);
					}
				}
			}
		}

		return $max_level;
	}

	/**
	 * User exercise time
	 *
	 * Define how much time user spent on exercise
	 *
	 * @param int $user_exerciseID User exercise ID
	 *
	 * @return string $time Time
	 **/
	public function UserExerciseTime($user_exerciseID) {

		$exercise = $this->db->get_where('user_exercises', array('id' => $user_exerciseID));

		if (count($exercise->result()) > 0) {

			$time_start = date_create($exercise->result()[0]->time);

			$this->db->order_by('id', 'desc');

			$actions = $this->db->get_where('user_actions', array('user_exerciseID' => $user_exerciseID));

			if (count($actions->result()) > 0) {

				$time_end = date_create($actions->result()[0]->time);

			} else {

				$time_end = $time_start;

			}

			$time_diff = date_diff($time_start, $time_end);

		} else {

			$time_diff = 0;

		}

		$time = $this->FormatTime($time_diff);

		return $time;
	}

	/**
	 * User exercise actions
	 *
	 * Define number of actions of user exercise
	 *
	 * @param int $user_exerciseID User exercise ID
	 *
	 * @return int $actions Actions
	 **/
	public function UserExerciseActions($user_exerciseID) {

		$actions = $this->db->get_where('user_actions', array('user_exerciseID' => $user_exerciseID));

		return $actions->result_array();
	}

	/**
	 * Format time
	 *
	 * @param string $seconds Seconds
	 *
	 * @return string $time Time
	 **/
	public function FormatTime($seconds) {

		$hour = $seconds->format('%h');
		$min = $seconds->format('%i');
		$sec = $seconds->format('%s');

		$time = ($hour!='0' ? '<b>'.$hour.'</b> ó ' : '').
				($min!='0' ? '<b>'.$min.'</b> p ' : '').
				'<b>'.$sec.'</b> mp ';

		return $time;
	}

	/**
	 * Delete user
	 *
	 * Delete activities of user
	 *
	 * @param int $userID User ID
	 *
	 * @return void
	 **/
	public function DeleteUser($userID) {

		// Delete actions
		$query = $this->db->query('DELETE FROM `user_actions`
				WHERE `user_actions`.`user_exerciseID` IN(
					SELECT `id` FROM `user_exercises`
						WHERE `user_exercises`.`userID` = '.$userID.'
				)');

		// Delete exercises
		$query = $this->db->query('DELETE FROM `user_exercises`
						WHERE `user_exercises`.`userID` = '.$userID);

		// Delete user
		$query = $this->db->delete('users', ['id' => $userID]);

		return;
	}
}

?>