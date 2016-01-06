<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_model {

	/**
	 * Class constructor
	 *
	 * @return	void
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

		$data['menu'] = $this->NavBarMenu();
		$data['search'] = $this->Database->getSearchData($classID, $topicID);
		$data['type'] = 'main';
		$data['title'] = NULL;

		return $data;
	}

	/**
	 * Get subtopic data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param  int 	  $id   Subtopic ID
	 * @return array  $data Subtopic data
	 */
	public function SubtopicData($id) {

		$data['menu'] 	= $this->NavBarMenu();
		$data['type'] 	= 'subtopic';
		$data['title'] 	= $this->Html->TitleSubtopic($id);
		$data['quests'] = $this->Exercises->getSubtopicQuests($id);
		// $data['exercises'] = $this->Exercises->getSubtopicExercises($id);

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

		$data['menu'] = $this->NavBarMenu();
		$data['title'] = $this->Html->TitleExercise($id);
		$data['type'] = 'exercise';

		if (!$level) {
			$this->load->model('Session');
			$level = $this->Session->getExerciseLevelNext($id);
		}

		$data['exercise'] = $this->Exercises->getExerciseData($id, $level);

		return $data;
	}

	/**
	 * Print navbar menu
	 *
	 * @return array  $menu Navbar menu
	 */
	public function NavBarMenu() {

		$classes = $this->db->get('classes');

		foreach ($classes->result() as $class) {

			$topics = $this->db->get_where('topics', array('classID' => $class->id));

			if (count($topics) > 0) {

				foreach ($topics->result() as $topic) {

					$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));

					if (count($subtopics) > 0) {

						foreach ($subtopics->result() as $subtopic) {

							$menu[$class->name][$topic->name][$subtopic->id] = $subtopic->name;

						}
					}

				}
			}
		}

		$data['html'] = $menu;

		return $data;
	}

	/**
	 * Print page title
	 *
	 * @param  int    $id   Subtopic/Excercise ID
	 * @param  string $type Page type (subtopic/exercise)
	 * @return string $title Page title
	 */
	public function Title($id, $type) {

		if ($id) {
			if ($type=='subtopic') {
				$title = $this->TitleSubtopic($id);
			} else {
				$title = $this->TitleExercise($id);
			}
			$title['type'] = $type;
		} else {
			$title['type'] = 'main';
		}

		return $title;
	}

	/**
	 * Print page title for exercise
	 *
	 * @param  int    $id   Subtopic/Excercise ID
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

		$level = $exercise->level;

		if (isset($this->session->userdata('results')[$id])) {
			$user_level = $this->session->userdata('results')[$id];
		} else {
			$user_level = 0;
		}

		for ($i=1; $i <= $level; $i++) {

			$img[$i] = 0;

			if ($i <= $user_level) {
				$img[$i] = 1;
			}
		}

		if (NULL !== $this->session->userdata('method')) {
			$method = $this->session->userdata('method');
			$questID = ($method == 'quest' ? $this->session->userdata('goal') : '');
		}

		// $title = $exercise->name;
		// $subtitle = $subtopic->name;
		// $href = base_url().'view/subtopic/'.$subtopic->id.'/'.$questID;

		return array(
			'img' 			=> $img,
			'title' 		=> $exercise->name,
			'subtitle' 		=> $subtopic->name,
			'subtopicID'	=> $subtopic->id,
			'questID' 		=> $questID
		);
	}

	/**
	 * Print page title for subtopic
	 *
	 * @param  int    $id   Subtopic/Excercise ID
	 * @param  string $type Page type (subtopic/exercise)
	 * @return string $html Html-code
	 */
	public function TitleSubtopic($id) {

		$this->load->model('Exercises');

		$subtopics = $this->db->get_where('subtopics', array('id' => $id));
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

		return array(
			'title' 	=> $title,
			'subtitle' 	=> $subtitle,
			'id'		=> $id,
			'href'		=> $href,
			// 'id_next' 	=> $this->Exercises->IDNextSubtopic($id)
		);
	}
}

?>