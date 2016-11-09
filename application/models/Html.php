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
		$this->load->helper('language');
		defined('RESOURCES_URL') OR define('RESOURCES_URL', base_url('resources/exercises'));
	}

	/**
	 * Get main page data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @return array $data Main page data
	 */
	public function MainData() {

		$data['maindata'] 	= $this->GetMainData();
		$data['type'] 		= 'main';

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

			$data['prev'] = $this->Database->SubtopicLink($id-1);
			$data['next'] = $this->Database->SubtopicLink($id+1);

		} elseif ($type == 'tag') {

			$data['prev'] = $this->Database->TagLink($id, 'previous');
			$data['next'] = $this->Database->TagLink($id, 'next');

		}	
		
		$data['results'] = $this->Session->GetResults();

		return $data;
	}

	/**
	 * Get breadcrumb for exercises
	 *
	 * Collects links to easier/harder/random exercises
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 * @param int    $exerciseID    Exercise ID
	 *
	 * @return array $data Breadcrumb data
	 */
	public function BreadCrumbExercise($classlabel, $subtopiclabel, $exerciselabel, $exerciseID) {

		$data['easier'] = $this->Database->GetEasierExercises($exerciseID);
		$data['harder'] = $this->Database->GetHarderExercises($classlabel, $subtopiclabel, $exerciselabel);
		$data['prev'] 	= $this->Database->ExerciseLink($exerciseID-1);
		$data['next'] 	= $this->Database->ExerciseLink($exerciseID+1);

		return $data;
	}

	/**
	 * Get subtopic data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 *
	 * @return array $data Subtopic data
	 */
	public function SubtopicData($classlabel, $subtopiclabel) {

		$subtopicID = $this->Database->SubtopicID($classlabel, $subtopiclabel);

		if ($subtopicID) {

			$data['type'] 		= 'subtopic';
			$data['exercises']	= $this->SubtopicExercises($classlabel, $subtopiclabel, $subtopicID);
			$data['results']	= $this->Session->GetResults('subtopic', $subtopiclabel);
			$data['breadcrumb'] = $this->BreadCrumb('subtopic', $subtopicID);
			$data['title']		= $this->Database->SubtopicTitle($subtopicID);
			$data['subtitle']	= $this->Database->ClassName($classlabel);


		} else {

			$data = NULL;

		}

		return $data;
	}

	/**
	 * Get tag data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param string $tag Tag
	 *
	 * @return array $data Subtopic data
	 */
	public function TagData($tag) {

		list($exercises, $tagName, $tagID) = $this->TagExercises($tag);

		if ($exercises) {

			$data['type'] 		= 'tag';
			$data['exercises']	= $exercises;
			$data['breadcrumb'] = $this->BreadCrumb('tag', $tagID);
			$data['results']	= $this->Session->GetResults();
			$data['title']		= mb_ucfirst($tagName);

		} else {

			$data = NULL;

		}

		return $data;
	}

	/**
	 * Get exercise data
	 *
	 * Collects all necessary parameters for template
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 * @param int    $exerciseID    Exercise ID
	 *
	 * @return array $data Exercise data
	 */
	public function ExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID) {

		$data['type'] 		= 'exercise';
		$data['results'] 	= $this->Session->GetResults();
		$data['exercise'] 	= $this->GetExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID);
		$data['breadcrumb'] = $this->BreadCrumbExercise($classlabel, $subtopiclabel, $exerciselabel, $exerciseID);

		$data['results']['id'] 	 = $exerciseID;
		$data['results']['type'] = 'exercise';

		$data['progress'] = $this->Session->UserProgress($exerciseID);

		return $data;
	}

	/**
	 * Get data for main page
	 *
	 * Gets all classes, topics & subtopics
	 *
	 * @return array $data Subtopics
	 */
	public function GetMainData() {

		$this->db->order_by('id', 'DESC');

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

							$subtopic_menu['label'] 		= $subtopic->label;
							$subtopic_menu['name'] 			= $subtopic->name;

							$subtopics_menu[] = $subtopic_menu;
						}
					}

					$topic_menu['name'] = $topic->name;
					$topic_menu['subtopics'] = $subtopics_menu;					

					// Collect final exercises separately
					if ($topic->name == 'Érettségi') {
						$final_exercises = $topic_menu;
						$final_exercises['classlabel'] = $class->label;
					} else {
						$topics_menu[] = $topic_menu;
					}
				}
			}

			$class_menu['name'] = $class->name;
			$class_menu['label'] = $class->label;
			$class_menu['topics'] = $topics_menu;

			$classes_menu[] = $class_menu;
		}

		$data['classes'] = $classes_menu;
		$data['final_exercises'] = $final_exercises;

		return $data;
	}

	/**
	 * Get ID of next exercise
	 *
	 * Checks whether user has completed all rounds of exercise.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $id_next Next exercise ID
	 */
	public function NextID($id) {

		$level_max  = $this->Database->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		$id_next = ($level_user < $level_max ? $id : $id+1);

 		return $id_next;
	}

	/**
	 * Get link of next exercise
	 *
	 * Checks whether user has completed all rounds of exercise.
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $link Next exercise link
	 */
	public function NextLink($id) {

		$level_max  = $this->Database->getMaxLevel($id);
		$level_user = $this->Session->getUserLevel($id);

		$id_next = ($level_user < $level_max ? $id : $id+1);

		$next = $this->Database->ExerciseLink($id_next);

 		return $next['link'];
	}
	
	/**
	 * Get exercises of subtopic
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param int    $subtopicID    Subtopic ID
	 *
	 * @return array $data Exercises
	 */
	public function SubtopicExercises($classlabel, $subtopiclabel, $subtopicID) {

		$query = $this->db->get_where('exercises', array('subtopicID' => $subtopicID));
		$exercises = $query->result();
		$data = NULL;

		if (count($exercises) > 0) {
			foreach ($exercises as $exercise) {

				$row['id']			= $exercise->id;
				$row['label'] 		= $exercise->label;
				$row['name'] 		= $exercise->name;
				$row['ex_order']	= $exercise->ex_order;
				$row['progress'] 	= $this->Session->UserProgress($exercise->id);
				$row['link'] 		= $this->Database->ExerciseLink($exercise->id);

				// Get tags from link (if exercise is linked to another)
				if ($this->Database->HasLink($exercise->id)) {
					$linkID 		= $this->Database->GetLinkID($exercise->id);
					$row['tags']	= $this->Database->GetExerciseTags($linkID);
				} else {
					$row['tags']	= $this->Database->GetExerciseTags($exercise->id);
				}
				
				$exercisedata = $this->GetExerciseData($classlabel, $subtopiclabel, $exercise->label, $exercise->id, $save=FALSE);
				$row['question']	= $exercisedata['question'];

				$data[] = $row;

			}
		}

		return $data;
	}

	/**
	 * Get exercises for tag
	 *
	 * @param string $tag Tag
	 *
	 * @return array  $data    Exercises
	 * @return string $tagName Tag name
	 * @return int    $tagID   Tag id
	 */
	public function TagExercises($tag) {

		// Get tag ID
		$query = $this->db->get_where('tags', ['label' => $tag]);
		$tagID = $query->result()[0]->id;
		$tagName = $query->result()[0]->name;

		// Get exercise IDs
		$query = $this->db->get_where('exercises_tags', ['tagID' => $tagID]);
		$exercises_tags = $query->result();
		$data = NULL;

		if (count($exercises_tags) > 0) {
			foreach ($exercises_tags as $exercise) {

				$exerciseID = $exercise->exerciseID;
				$query = $this->db->get_where('exercises', ['id' => $exerciseID]);
				$exercise = $query->result()[0];

				$row['id']			= $exerciseID;
				$row['label'] 		= $exercise->label;
				$row['name'] 		= $exercise->name;
				$row['progress'] 	= $this->Session->UserProgress($exerciseID);
				$row['link'] 		= $this->Database->ExerciseLink($exerciseID);
				
				// Get tags from link (if exercise is linked to another)
				if ($this->Database->HasLink($exerciseID)) {
					$linkID 		= $this->Database->GetLinkID($exerciseID);
					$row['tags']	= $this->Database->GetExerciseTags($linkID);
				} else {
					$row['tags']	= $this->Database->GetExerciseTags($exerciseID);
				}

				$classlabel 	= $this->Database->getClassLabel($exerciseID);
				$subtopiclabel 	= $this->Database->getSubtopicLabel($exerciseID);
				$subtopicname 	= $this->Database->SubtopicName($classlabel, $subtopiclabel);

				$row['classlabel'] 		= $classlabel;
				$row['subtopiclabel'] 	= $subtopiclabel;
				$row['subtopicname'] 	= $subtopicname;

				$exercisedata = $this->GetExerciseData($classlabel, $subtopiclabel, $exercise->label, $exerciseID, $save=FALSE);
				$row['question']	= $exercisedata['question'];

				$data[] = $row;
			}
		}

		return array($data, $tagName, $tagID);
	}

	/**
	 * Get exercise data
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 * @param string $exerciselabel Exercise label
	 * @param int    $exerciseID    Exercise ID
	 * @param bool   $save          Should we save exercise data in session?
	 *
	 * @return array $data Exercise data
	 */
	public function GetExerciseData($classlabel, $subtopiclabel, $exerciselabel, $exerciseID, $save=TRUE) {

		$this->load->helper('string');
		$this->load->model('Hints');

		// Get exercise level
		$level_user = $this->Session->getUserLevel($exerciseID);
		$level_max = $this->Database->getMaxLevel($exerciseID);

		$level = min($level_max, ++$level_user);

		// Check if exercise has link to other one
		if ($this->Database->HasLink($exerciseID)) {

			$path 		= $this->Database->GetLinkPath($exerciseID);
			$lib_name 	= str_replace("/", "", $path);

		} else {
			
			$path 		= $classlabel.'/'.$subtopiclabel.'/'.$exerciselabel;
			$lib_name 	= $classlabel.$subtopiclabel.$exerciselabel;
		}

		// Generate exercise
		$this->load->library($path, NULL, $lib_name);

		$data = $this->$lib_name->Generate($level);

		if (!isset($data['type'])) {
			if (!isset($data['options'])) {
				$data['type'] = 'int';
			} elseif (is_array($data['options'])) {
				$data['type'] = 'quiz';
			}
		}

		if ($data['type'] == 'quiz') {
			$data = $this->getColumnWidth($data);
		} elseif (($data['type'] == 'array'
				|| $data['type'] == 'list')
				&& !isset($data['labels'])) {
			$data['labels'] = array_fill(0, count($data['correct']), NULL);
		}

		$data = $this->Hints->AddHints($exerciseID, $data);
		
		$hash = random_string('alnum', 16);

		if ($save) {
			$this->Session->SaveExerciseData($exerciseID, $level, $data, $hash);
		}

		$data['hash']			= $hash;
		$data['classlabel'] 	= $classlabel;
		$data['subtopiclabel'] 	= $subtopiclabel;
		$data['exerciselabel'] 	= $exerciselabel;

		return $data;
	}

	/**
	 * Get answer length
	 *
	 * Calculates maximum length of answers from options
	 *
	 * @param array $data Exercise data
	 *
	 * @return array $data Exercise data (completed)
	 */
	public function getColumnWidth($data) {

		$lengths = [];

		foreach ($data['options'] as $option) {

			$lengths[] = count(str_split($option));
		}

		$max_length = max($lengths);
		$min_length = min($lengths);

		if ($max_length < 2) {
			$width = 2;
		} elseif ($max_length < 10) {
			$width = 4;
		} elseif ($max_length < 26) {
			$width = 6;
		} else {
			$width = 8;
		}

		$data['align'] = ($max_length == $min_length ? 'center' : 'left');
		$data['width'] = $width;

		return $data;
	}
}

?>