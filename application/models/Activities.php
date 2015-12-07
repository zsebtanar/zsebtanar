<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activities extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct() {

		$this->load->helper('url');
	}

	/**
	 * Get session length
	 *
	 * Calculates length of session in seconds by taking the time difference
	 * between its first and last action
	 *
	 * @param  int $id     Session ID
	 * @return int $length Session length (s)
	 */
	public function GetSessionLength($id) {

		$query = $this->db->query(
					'SELECT MIN(`time`) as `time_min` FROM `quests`
						WHERE `id` IN(
							SELECT `id` FROM `quests`
							WHERE `sessionID` = '.$id.' 
						)');

		if ($query->num_rows() == 0) {
			return 0;
		} else {
			$time_min = strtotime($query->result()[0]->time_min);
		}

		$query = $this->db->query(
					'SELECT MAX(`time`) as `time_max` FROM `actions`
						WHERE `questID` IN(
							SELECT `id` FROM `quests`
							WHERE `sessionID` = '.$id.' 
						)');

		if ($query->num_rows() == 0) {
			return 0;
		} else {
			$time_max = strtotime($query->result()[0]->time_max);
		}

		$length = $time_max - $time_min;

		return $length;
	}

	/**
	 * Get quest length
	 *
	 * Calculates length of quest in seconds by taking the time difference
	 * between its first and last action
	 *
	 * @param  int $id     Quest ID
	 * @return int $length Quest length (s)
	 */
	public function GetQuestLength($id) {

		$query 		= $this->db->query('SELECT `time` FROM `quests` WHERE `id` = '.$id);
		$time_start = strtotime($query->result()[0]->time);

		$query 		= $this->db->query(
						'SELECT MAX(`time`) as `time_max` FROM `actions`
							WHERE `questID` = '.$id);
		if (NULL !== $query->result()[0]->time_max) {
			$time_end = strtotime($query->result()[0]->time_max);
		} else {
			$time_end = $time_start;
		}

		$length = $time_end - $time_start;

		return $length;
	}

	/**
	 * Get action length
	 *
	 * Calculates length of action in seconds by taking the time difference
	 * between its first and last action
	 *
	 * @param  int $id     Action ID
	 * @return int $length Action length (s)
	 */
	public function GetActionLength($id) {

		$id_prev 	= $id-1;
		$query 		= $this->db->query('SELECT `time` FROM `actions` WHERE `id` = '.$id_prev);

		if ($query->num_rows() == 0) {
			$query 		= $this->db->query('SELECT `questID` FROM `actions` WHERE `id` = '.$id);
			$questID 	= $query->result()[0]->questID;
			$query 		= $this->db->query('SELECT `time` FROM `quests` WHERE `id` = '.$questID);
		}

		$time_start = strtotime($query->result()[0]->time);

		$query 		= $this->db->query('SELECT `time` FROM `actions` WHERE `id` = '.$id);
		if ($query->num_rows() > 0) {
			$time_end = strtotime($query->result()[0]->time);
		} else {
			$time_end = $time_start;
		}

		$length 	= $time_end - $time_start;

		return $length;
	}

	/**
	 * Get session start
	 *
	 * Calculates starting time of session (s)
	 *
	 * @param  int $id    Session ID
	 * @return int $start Session start (s)
	 */
	public function GetSessionStart($id) {

		$query = $this->db->query(
					'SELECT MIN(`time`) as `time_start` FROM `actions`
						WHERE `questID` = (
							SELECT MIN(`id`) FROM `quests`
							WHERE `sessionID` = '.$id.' 
						)');

		$start = $query->result()[0]->time_start;

		return $start;
	}

	/**
	 * Get session results
	 *
	 * Calculates number of total/completed/not finished quests for session
	 *
	 * @param  int $id      Session ID
	 * @return int $results Session results
	 */
	public function GetSessionResults($id) {

		$query = $this->db->query('SELECT COUNT(`id`) AS `total` FROM `quests` WHERE `sessionID` = '.$id);
		$results['total'] = $query->result()[0]->total;

		$query = $this->db->query('SELECT COUNT(`id`) AS `total` FROM `quests` WHERE `status` = \'COMPLETED\' AND `sessionID` = '.$id);
		$results['completed'] = $query->result()[0]->total;

		$results['not_finished'] = $results['total'] - $results['completed'];

		return $results;
	}

	/**
	 * Get session quests
	 *
	 * Calculates number of correct/wrong/missing actions for quest
	 *
	 * @param  int $id      Quest ID
	 * @return int $results Quest results
	 */
	public function GetQuestResults($id) {

		$query = $this->db->query('SELECT COUNT(`id`) AS `total` FROM `actions` WHERE `questID` = '.$id);
		$results['total'] = $query->result()[0]->total;

		$query = $this->db->query('SELECT COUNT(`id`) AS `correct` FROM `actions`
									WHERE `result` = \'CORRECT\' AND `questID` = '.$id);
		$results['correct'] = $query->result()[0]->correct;

		$query = $this->db->query('SELECT COUNT(`id`) AS `wrong` FROM `actions`
									WHERE `result` = \'WRONG\' AND `questID` = '.$id);
		$results['wrong'] = $query->result()[0]->wrong;

		$results['not_done'] = $results['total'] - $results['correct'] - $results['wrong'];

		return $results;
	}

	/**
	 * Get class name
	 *
	 * Searches for class name of exercise/subtopic.
	 *
	 * @param  int    $id   ID
	 * @param  string $type Id type (exercise/subtopic)
	 *
	 * @return string $class Class name
	 */
	public function GetClassName($id, $type) {

		if ($type == 'exercise') {

			$query = $this->db->query(
				'SELECT DISTINCT `classes`.`name` as `osztaly` FROM `classes`
					INNER JOIN `topics` ON `classes`.`id` = `topics`.`classID`
					INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
					INNER JOIN `exercises` ON `exercises`.`subtopicID` = `exercises`.`subtopicID`
						WHERE `exercises`.`id` = '.$id);
			$class = $query->result()[0]->osztaly;

		} elseif ($type == 'subtopic') {

			$query = $this->db->query(
				'SELECT DISTINCT `classes`.`name` as `osztaly` FROM `classes`
					INNER JOIN `topics` ON `classes`.`id` = `topics`.`classID`
					INNER JOIN `subtopics` ON `topics`.`id` = `subtopics`.`topicID`
						WHERE `subtopics`.`id` = '.$id);
			$class = $query->result()[0]->osztaly;

		}

		return $class;
	}

	/**
	 * Get sessions
	 *
	 * @return array $sessions Session data
	 */
	public function getSessions() {

		$query = $this->db->get('sessions');

		if ($query->num_rows() > 0) {

			foreach ($query->result() as $row) {

				$id 		= $row->id;
				$length 	= $this->GetSessionLength($id);
				$quests		= $this->GetSessionResults($id);
				$start		= $this->GetSessionStart($id);

				$max_length = (isset($max_length) ? max($max_length, $length) : $length);
				$max_quests = (isset($max_quests) ? max($max_quests, $quests['total']) : $quests['total']);

				$session['id'] 				= $id;
				$session['start'] 			= $start;
				$session['length'] 			= $length;
				$session['length_label'] 	= gmdate("H:i:s", $length);
				$session['quests1'] 		= $quests['completed'];
				$session['quests2'] 		= $quests['not_finished'];
				$session['quests1_label'] 	= $quests['completed'];
				$session['quests2_label'] 	= $quests['not_finished'];

				$sessions[] = $session;
			}

			foreach ($sessions as $index => $session) {

				if ($max_length > 0) {
					$session['length'] 		= round($session['length']/$max_length*100);
				}

				if ($max_quests > 0) {
					$session['quests1']		= round($session['quests1']/$max_quests*100);
					$session['quests2']		= round($session['quests2']/$max_quests*100);
				}

				$sessions[$index]		= $session;
			}
		} else {
			$sessions = [];
		}

		return $sessions;
	}

	/**
	 * Get quests
	 *
	 * @param int $id Session id
	 *
	 * @return array $quests Session data
	 */
	public function getQuests($id) {

		$query = $this->db->get_where('quests', array('sessionID' => $id));

		if ($query->num_rows() > 0) {

			foreach ($query->result() as $row) {

				$id 		= $row->id;
				$length 	= $this->GetQuestLength($id);
				$actions 	= $this->GetQuestResults($id);

				$status = ($row->status == 'COMPLETED' ? 'success' : 'danger');
				$method = ($row->method == 'exercise' ? 'feladat' : 'témakör');

				$max_length = (isset($max_length) ? max($max_length, $length) : $length);
				$max_actions = (isset($max_actions) ? max($max_actions, $actions['total']) : $actions['total']);

				$quest['id'] 			= $id;
				$quest['name'] 			= $row->name;
				$quest['class'] 		= $row->class;
				$quest['method'] 		= $method;
				$quest['status'] 		= $status;
				$quest['length'] 		= $length;
				$quest['length_label'] 	= gmdate("H:i:s", $length);

				$quest['actions1'] 		= $actions['correct'];
				$quest['actions2'] 		= $actions['wrong'];
				$quest['actions3'] 		= $actions['not_done'];
				$quest['actions1_label'] 	= $actions['correct'];
				$quest['actions2_label'] 	= $actions['wrong'];
				$quest['actions3_label'] 	= $actions['not_done'];

				$quests[] = $quest;
			}

			foreach ($quests as $index => $quest) {

				if ($max_length > 0) {
					$quest['length'] 	= round($quest['length']/$max_length*100);
				}

				if ($max_actions > 0) {
					$quest['actions1'] = round($quest['actions1']/$max_actions*100);
					$quest['actions2'] = round($quest['actions2']/$max_actions*100);
					$quest['actions3'] = round($quest['actions3']/$max_actions*100);
				}

				$quests[$index]		= $quest;
			}

		} else {
			$quests = NULL;
		}

		return $quests;
	}

	/**
	 * Get quest name
	 *
	 * Searches for name of quest
	 *
	 * @param int $id Quest ID
	 *
	 * @return string $class Class name
	 */
	public function GetQuestName($id) {

		$query = $this->db->get_where('quests', array('id' => $id));
		$name = $query->result()[0]->name;

		return $name;
	}

	/**
	 * Get actions
	 *
	 * @param int $questID Quest id
	 *
	 * @return array $actions Action data
	 */
	public function getActions($questID) {

		$query = $this->db->get_where('actions', array('questID' => $questID));

		if ($query->num_rows() > 0) {

			foreach ($query->result() as $row) {

				$id 		= $row->id;
				$length 	= $this->GetActionLength($id);

				switch ($row->result) {
					case 'CORRECT':
						$status = 'success';
						break;
					case 'WRONG':
						$status = 'danger';
						break;
					case 'NOT_DONE':
						$status = 'warning';
						break;
				}

				$max_length = (isset($max_length) ? max($max_length, $length) : $length);

				$action['id'] 			= $id;
				$action['name'] 		= $row->name;
				$action['todo'] 		= $row->todo;
				$action['status'] 		= $status;
				$action['length'] 		= $length;
				$action['length_label'] = gmdate("H:i:s", $length);
				$action['icons'] 		= $this->GetProgressIcons($row->level, $row->level_max, $row->result);

				$actions[] = $action;
			}

			foreach ($actions as $index => $action) {

				if ($max_length > 0) {
					$action['length'] 	= round($action['length']/$max_length*100);
				}

				$actions[$index]		= $action;
			}

		} else {
			$actions = NULL;
		}

		return $actions;
	}

	/**
	 * Get glyphicons for exercise
	 *
	 * @param  int 	  $level     User level
	 * @param  int 	  $level_max Exercise level
	 * @param  string $result    Result (CORRECT/WRONG/NOT_DONE)
	 *
	 * @return array $data Data
	 */
	public function GetProgressIcons($level, $level_max, $result) {

		for ($i=0; $i < $level_max; $i++) { 
			if ($i < $level) {
				switch ($result) {
					case 'CORRECT':
						$status = 'success';
						$icon 	= 'ok-sign';
						break;
					case 'WRONG':
						$status = 'danger';
						$icon 	= 'remove-sign';
						break;
					case 'NOT_DONE':
						$status = 'warning';
						$icon 	= 'question-sign';
						break;
				}
			} else {
				$status = 'default';
				$icon 	= 'info-sign';
			}

			$icons['status'] = $status;
			$icons['icon'] = $icon;

			$data[] = $icons;
		}

		return $data;
	}
}

?>