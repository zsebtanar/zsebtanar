<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->load->helper('url');
	}

	/**
	 * Get main page data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return array $data Main page data
	 */
	public function MainData($classID=NULL, $topicID=NULL) {

		$data['topics'] 	= $this->getTopics();
		$data['type'] 		= 'main';
		$data['titledata'] 	= NULL;
		$data['results'] 	= $this->Session->GetResults();

		return $data;
	}

	/**
	 * Get breadcrumb
	 *
	 * Collects links to current class/suptopic/quest/exercise etc.
	 *
	 * @param string $type Type of id
	 * @param int    $id   Id
	 *
	 * @return array $data Breadcrumb data
	 */
	public function BreadCrumb($type, $id) {

		if ($type == 'subtopic') {

			$data['class']	= $this->Database->GetSubtopicClass($id);
			$data['topic']	= $this->Database->GetSubtopicTopic($id);

			$query = $this->db->get_where('subtopics', array('id' => $id));
			$data['subtopic'] = $query->result_array()[0];

		} elseif ($type == 'exercise') {

			$subtopicID = $this->Exercises->getSubtopicID($id);

			$data['subtopic']['id']	= $subtopicID;
			$data['subtopic']['name']	= $this->Exercises->getSubtopicName($id);

			$data['quest']	= $this->Database->GetExerciseQuest($id);

			$data['class']	= $this->Database->GetSubtopicClass($subtopicID);
			$data['topic']	= $this->Database->GetSubtopicTopic($subtopicID);

			$query = $this->db->get_where('exercises', array('id' => $id));
			$data['exercise'] = $query->result_array()[0];

		}

		return $data;
	}

	/**
	 * Get subtopic data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return array $data Subtopic data
	 */
	public function SubtopicData($id) {

		$data['type'] 		= 'subtopic';
		$data['quests']		= $this->Exercises->getSubtopicQuests($id);
		$data['results']	= $this->Session->GetResults('subtopic', $id);
		$data['breadcrumb'] = $this->BreadCrumb('subtopic', $id);

		$data['title']['current'] 	= $this->TitleSubtopic($id);
		$data['title']['prev'] 		= $this->TitleSubtopic($id-1);
		$data['title']['next'] 		= $this->TitleSubtopic($id+1);

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $id    Exercise ID
	 * @param int $level Exercise level
	 *
	 * @return array $data Exercise data
	 */
	public function ExerciseData($id, $level) {

		$data['type'] 		= 'exercise';
		$data['results'] 	= $this->Session->GetResults('exercise', $id);
		$data['exercise'] 	= $this->Exercises->GetExerciseData($id, $level);
		$data['breadcrumb'] = $this->BreadCrumb('exercise', $id);

		$data['results']['id'] = $id;
		$data['results']['type'] = 'exercise';

		$data['title']['current'] 	= $this->TitleExercise($id);

		return $data;
	}

	/**
	 * Get navbar menu
	 *
	 * @return array $data Navbar menu
	 */
	public function getTopics() {

		$this->db->order_by('id');
		$classes = $this->db->get('classes');

		foreach ($classes->result() as $class) {

			$this->db->order_by('id');
			$topics = $this->db->get_where('topics', array('classID' => $class->id));

			if (count($topics) > 0) {

				foreach ($topics->result() as $topic) {

					$this->db->order_by('id');
					$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));

					if (count($subtopics) > 0) {

						foreach ($subtopics->result() as $subtopic) {

							$quests = $this->Exercises->getSubtopicQuests($subtopic->id);

							if ($quests) {
								$iscomplete = $this->Session->isSubtopicComplete($subtopic->id);

								$menu[$class->name][$topic->name][$subtopic->id] = array(
									'name' => $subtopic->name,
									'iscomplete' => $iscomplete
								);
							}
						}
					}

				}
			}
		}

		$data['html'] = $menu;

		return $data;
	}

	/**
	 * Get title data for exercise
	 *
	 * @param int $id Excercise ID
	 *
	 * @return array $data Exercise data
	 */
	public function TitleExercise($id) {

		$exercises = $this->db->get_where('exercises', array('id' => $id));

		if (count($exercises->result()) > 0) {
			$exercise = $exercises->result()[0];

			$subtopicID = $this->Exercises->getSubtopicID($id);
			$subtopicName = $this->Exercises->getSubtopicName($id);

			$level_user = $this->Session->getUserLevel($id);
			$level_max = $this->Exercises->getMaxLevel($id);

			$questID = $exercise->questID;

			$progress = $this->Session->getUserProgress($id);

			$data = array(
				'id'			=> $id,
				'level_user' 	=> $level_user,
				'level_max'	 	=> $level_max,
				'progress'		=> $progress,
				'title' 		=> $exercise->name,
				'subtitle' 		=> $subtopicName,
				'subtopicID'	=> $subtopicID,
				'questID' 		=> $questID,
				'status'		=> $exercise->status
			);
		} else {

			$data = NULL;
			
		}

		return $data;
	}

	/**
	 * Get page title for subtopic
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $data Html-code
	 */
	public function TitleSubtopic($id) {

		$this->load->model('Exercises');

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));

		if (count($subtopics->result()) > 0) {

			$subtopic = $subtopics->result()[0];

			$this->db->select('classes.name')
					->from('subtopics')
					->join('topics', 'topics.id = subtopics.topicID', 'inner')
					->join('classes', 'classes.id = topics.classID', 'inner');
			$classes = $this->db->get();
			$class = $classes->result()[0];

			$title = $subtopic->name;
			$subtitle = $class->name;

			$href = base_url().'view/subtopic/'.$subtopic->id;

			$data = array(
				'title' 	=> $title,
				'subtitle' 	=> $subtitle,
				'id'		=> $id,
				'href'		=> $href
			);

		} else {

			$data = NULL;

		}

		return $data;
	}
}

?>