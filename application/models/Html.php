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

		$data['menu'] 		= $this->NavBarMenu();
		$data['type'] 		= 'main';
		$data['titledata'] 	= NULL;
		$data['results'] 	= $this->Session->GetResults();

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

		$data['menu'] 	= $this->NavBarMenu();
		$data['type'] 	= 'subtopic';
		$data['quests'] = $this->Exercises->getSubtopicQuests($id);
		$data['results'] = $this->Session->GetResults();

		$data['title']['current'] 	= $this->TitleSubtopic($id);
		$data['title']['prev'] 		= $this->TitleSubtopic($id-1);
		$data['title']['next'] 		= $this->TitleSubtopic($id+1);

		$data['results']['id'] = $id;
		$data['results']['type'] = 'subtopic';

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param int $id    Exercise ID
	 *
	 * @return array $data Exercise data
	 */
	public function ExerciseData($id) {

		$data['menu'] 		= $this->NavBarMenu();
		$data['titledata'] 	= $this->TitleExercise($id);
		$data['type'] 		= 'exercise';
		$data['results'] 	= $this->Session->GetResults();
		$data['exercise'] 	= $this->Exercises->GetExerciseData($id);

		$data['results']['id'] = $id;
		$data['results']['type'] = 'exercise';

		return $data;
	}

	/**
	 * Get navbar menu
	 *
	 * @return array $data Navbar menu
	 */
	public function NavBarMenu() {

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

		$data['html'] = $menu;

		return $data;
	}

	/**
	 * Get page title for exercise
	 *
	 * @param int $id Excercise ID
	 *
	 * @return string $html Html-code
	 */
	public function TitleExercise($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$exercise = $query->result()[0];

		$this->db->select('subtopics.name, subtopics.id')
				->distinct()
				->from('subtopics')
				->join('quests', 'subtopics.id = quests.subtopicID', 'inner')
				->join('exercises', 'quests.id = exercises.questID', 'inner')
				->where('exercises.id', $id);
		$subtopics = $this->db->get();
		$subtopic = $subtopics->result()[0];

		$level_user = $this->Session->getUserLevel($id);
		$level_max = $this->Exercises->getMaxLevel($id);

		$questID = $exercise->questID;

		$progress = $this->Session->getUserProgress($id);

		return array(
			'level_user' 	=> $level_user,
			'level_max'	 	=> $level_max,
			'progress'		=> $progress,
			'title' 		=> $exercise->name,
			'subtitle' 		=> $subtopic->name,
			'subtopicID'	=> $subtopic->id,
			'questID' 		=> $questID
		);
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