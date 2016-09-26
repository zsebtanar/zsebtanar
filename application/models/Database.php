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
	 * Gets id for exercise
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 *
	 * @return int $id Exercise ID
	 */
	public function ExerciseID($classlabel, $subtopiclabel, $exerciselabel) {

		$query = $this->db->query(
			'SELECT `exercises`.`id` FROM `exercises`
				WHERE `exercises`.`subtopicID` IN (
					SELECT `subtopics`.`id` FROM `subtopics`
						WHERE `subtopics`.`topicID` IN (
							SELECT `topics`.`id` FROM `topics`
								WHERE `topics`.`classID` IN (
									SELECT `classes`.`id` FROM `classes`
										WHERE `classes`.`label` = \''.$classlabel.'\'
									)
							) AND `subtopics`.`label` = \''.$subtopiclabel.'\'
					) AND `exercises`.`label` = \''.$exerciselabel.'\'');

		$id = (count($query->result()) > 0 ? $query->result()[0]->id : NULL);

 		return $id;
	}

	/**
	 * Gets id for subtopic
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 *
	 * @return int $id Subtopic ID
	 */
	public function SubtopicID($classlabel, $subtopiclabel) {

		$query = $this->db->query(
			'SELECT `subtopics`.`id` FROM `subtopics`
				WHERE `subtopics`.`topicID` IN (
					SELECT `topics`.`id` FROM `topics`
						WHERE `topics`.`classID` = (
							SELECT `classes`.`id` FROM `classes`
								WHERE `classes`.`label` = \''.$classlabel.'\''.
							')
					) AND `subtopics`.`label` = \''.$subtopiclabel.'\'');

		if ($query->num_rows() > 0) {

			$id = $query->result()[0]->id;
		
		} else {

			$id = NULL;

		}
		

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
	 * @param int    $id     Exercise ID
	 * @param string $access How was this exercise accessed by user?
	 *
	 * @return string $href Link
	 */
	public function ExerciseLink($id, $access=NULL) {

		$exercises = $this->db->get_where('exercises', array('id' => $id));

		if (count($exercises->result()) == 1) {

			$this->db->select('classes.label');
			$this->db->from('classes');
			$this->db->join('topics', 'topics.classID = classes.id');
			$this->db->join('subtopics', 'subtopics.topicID = topics.id');
			$this->db->join('exercises', 'exercises.subtopicID = subtopics.id');
			$this->db->where('exercises.id', $id);
			$classes = $this->db->get();

			$this->db->select('subtopics.label');
			$this->db->from('subtopics');
			$this->db->join('exercises', 'exercises.subtopicID = subtopics.id');
			$this->db->where('exercises.id', $id);
			$subtopics = $this->db->get();

			$class 		= $classes->result()[0];
			$subtopic 	= $subtopics->result()[0];
			$exercise 	= $exercises->result()[0];

			if ($this->Session->CheckLogin() || $exercise->status == 'OK') {

				$title = $exercise->name;
				$link = base_url().$class->label.'/'.$subtopic->label.'/'.$exercise->label.'/'.$access;
				$name = $exercise->name;

			} else {

				$link = base_url();
				$name = 'Kezdőlap';

			}

		} else {

			$link = base_url();
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

			$this->db->select('classes.label');
			$this->db->from('classes');
			$this->db->join('topics', 'topics.classID = classes.id');
			$this->db->join('subtopics', 'subtopics.topicID = topics.id');
			$this->db->where('subtopics.id', $id);
			$classes = $this->db->get();

			$subtopic = $subtopics->result()[0];
			$class = $classes->result()[0];
			$link = base_url().$class->label.'/'.$subtopic->label;
			$name = $subtopic->name;

		} else {

			$link = base_url();
			$name = 'Kezdőlap';

		}

		return array(
			'link' => $link,
			'name' => $name
		);
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
}

?>