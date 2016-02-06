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

		$data['subtopics'] 	= $this->getSubtopics($classID, $topicID);
		$data['classID'] 	= $classID;
		$data['topicID'] 	= $topicID;
		$data['type'] 		= 'main';
		$data['titledata'] 	= NULL;
		$data['results'] 	= $this->Session->GetResults();

		return $data;
	}

	/**
	 * Get breadcrumb
	 *
	 * Collects links to current class/suptopic/exercise etc.
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

			$data['link_prev'] = $this->getSubtopicLink($id-1);
			$data['link_next'] = $this->getSubtopicLink($id+1);

		} elseif ($type == 'exercise') {

			$subtopicID = $this->Exercises->getSubtopicID($id);

			$data['subtopic']['id']	= $subtopicID;
			$data['subtopic']['name']	= $this->Exercises->getSubtopicName($id);

			$data['class']	= $this->Database->GetSubtopicClass($subtopicID);
			$data['topic']	= $this->Database->GetSubtopicTopic($subtopicID);

			$query = $this->db->get_where('exercises', array('id' => $id));
			$data['exercise'] = $query->result_array()[0];

			$data['link_prev'] = $this->Exercises->getExerciseLink($id-1);
			$data['link_next'] = $this->Exercises->getExerciseLink($id+1);

		}

		return $data;
	}

	/**
	 * Get subtopic data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $subtopicID Subtopic ID
	 * @param int $exerciseID Exercise ID
	 *
	 * @return array $data Subtopic data
	 */
	public function SubtopicData($subtopicID=NULL, $exerciseID=NULL) {

		$data['type'] 		= 'subtopic';
		$data['exercises']		= $this->Exercises->getSubtopicExercises($subtopicID, $exerciseID);
		$data['results']	= $this->Session->GetResults('subtopic', $subtopicID);
		$data['breadcrumb'] = $this->BreadCrumb('subtopic', $subtopicID);
		$data['title']		= $this->SubtopicTitle($subtopicID);

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

		$data['progress'] 	= $this->Session->getUserProgress($id);

		return $data;
	}

	/**
	 * Get subtopics
	 *
	 * Gets all subtopics for each topic, and all topics for each class
	 *
	 * @param int $classID Class id
	 * @param int $topicID Topic id
	 *
	 * @return array $data Subtopics
	 */
	public function getSubtopics($classID=NULL, $topicID=NULL) {

		$this->db->order_by('id');
		$classes = $this->db->get('classes');
		

		foreach ($classes->result() as $class) {

			$this->db->order_by('id');
			$topics = $this->db->get_where('topics', array('classID' => $class->id));
			$topics_menu = [];

			if (count($topics) > 0) {

				foreach ($topics->result() as $topic) {

					$this->db->order_by('id');
					$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));
					$subtopics_menu = [];

					if (count($subtopics) > 0) {

						foreach ($subtopics->result() as $subtopic) {

							$subtopic_menu['id'] = $subtopic->id;
							$subtopic_menu['name'] = $subtopic->name;

							$subtopics_menu[] = $subtopic_menu;
						}
					}

					$topic_menu['id'] = $topic->id;
					$topic_menu['name'] = $topic->name;
					$topic_menu['subtopics'] = $subtopics_menu;

					if ($this->Session->CheckLogin() || $topic->id == $topicID || (!$topicID && $topic->classID == $classID)) {
						$topic_menu['class'] = 'in';
					} else {
						$topic_menu['class'] = '';
					}
					

					$topics_menu[] = $topic_menu;
				}
			}

			$class_menu['id'] = $class->id;
			$class_menu['name'] = $class->name;
			$class_menu['topics'] = $topics_menu;

			if ($this->Session->CheckLogin() || $class->id == $classID) {
				$class_menu['class'] = 'in';
			} else {
				$class_menu['class'] = '';
			}

			$classes_menu[] = $class_menu;
		}

		$data['classes'] = $classes_menu;

		return $data;
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

			$title = NULL;

		}

		return $title;
	}

	/**
	 * Get link for subtopic
	 *
	 * @param int $id Subtopic ID
	 *
	 * @return string $href Link
	 */
	public function getSubtopicLink($id) {

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));

		if (count($subtopics->result()) > 0) {

			$subtopic = $subtopics->result()[0];
			$href = base_url().'view/subtopic/'.$subtopic->id;

		} else {

			$href = base_url().'view/main/';

		}

		return $href;
	}
}

?>