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
		$this->load->helper('language');
		$this->load->dbforge();
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
	 * Gets subtopic name
	 *
	 * @param string $classlabel    Class label
	 * @param string $subtopiclabel Subtopic label
	 *
	 * @return string $name Subtopic name
	 */
	public function SubtopicName($classlabel, $subtopiclabel) {

		$query = $this->db->query(
			'SELECT `subtopics`.`name` FROM `subtopics`
				WHERE `subtopics`.`topicID` IN (
					SELECT `topics`.`id` FROM `topics`
						WHERE `topics`.`classID` = (
							SELECT `classes`.`id` FROM `classes`
								WHERE `classes`.`label` = \''.$classlabel.'\''.
							')
					) AND `subtopics`.`label` = \''.$subtopiclabel.'\'');

		if ($query->num_rows() > 0) {

			$name = $query->result()[0]->name;
		
		} else {

			$name = NULL;

		}
		

 		return $name;
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
	 * Get class label for exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return int $subtopiclabel Subtopic label
	 */
	public function getClassLabel($id) {

		$query = $this->db->get_where('exercises', array('id' => $id));
		$subtopicID = $query->result()[0]->subtopicID;
		$query = $this->db->get_where('subtopics', array('id' => $subtopicID));
		$topicID = $query->result()[0]->topicID;
		$query = $this->db->get_where('topics', array('id' => $topicID));
		$classID = $query->result()[0]->classID;
		$query = $this->db->get_where('classes', array('id' => $classID));
		$subtopiclabel = $query->result()[0]->label;

 		return $subtopiclabel;
	}

	/**
	 * Get class name
	 *
	 * @param string $classlabel Class label
	 *
	 * @return string $classname Class name
	 */
	public function ClassName($classlabel) {

		$query = $this->db->get_where('classes', array('label' => $classlabel));
		$classname = $query->result()[0]->name;

 		return $classname;
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
	 *
	 * @return string $href Link
	 */
	public function ExerciseLink($id) {

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

			$this->load->model('Session');

			$title = $exercise->name;
			$link = base_url().$class->label.'/'.$subtopic->label.'/'.$exercise->label;
			$name = $exercise->name;

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

		if (count($subtopics->result())) {

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
	 * Get link for tag
	 *
	 * @param int    $id   Subtopic ID
	 * @param string $type Type of link ('previous'/'next')
	 *
	 * @return string $link Link
	 */
	public function TagLink($id, $type) {

		$link = base_url();
		$name = 'Kezdőlap';

		$tags = $this->db->get_where('tags', array('id' => $id));

		$order 		= ($type == 'previous' ? 'DESC' : 'ASC');
		$relation 	= ($type == 'previous' ? '<' : '>');

		if (count($tags->result()) > 0) {

			$currenttag = $tags->result()[0];

			$query = $this->db->query("SELECT * FROM tags ".
					"WHERE name ".$relation." '".$currenttag->name."' ".
					"ORDER BY name ".$order." LIMIT 1");

			if($query->num_rows() > 0) {

				$tag = $query->result()[0];

				$link = base_url().'view/tag/'.$tag->label;
				$name = mb_ucfirst($tag->name);

			}
		}

		return array(
			'link' => $link,
			'name' => $name
		);
	}

	/**
	 * Get exercises for specific tag
	 *
	 * @param string $tag Tag name
	 *
	 * @return void
	 **/
	public function GetTagExercises($tag) {

		$this->db->select('*');
		$this->db->order_by('name');
		$this->db->like('name', $tag);
		$query = $this->db->get('tags');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$new_row['label'] = $row['name'];
				$new_row['value'] = base_url().'view/tag/'.$row['label'];
				$row_set[] = $new_row; //build an array
			}
			echo json_encode($row_set); //format the array into json data
		}

		return;
	}

	/**
	 * Get tags for specific exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return array $tags Tags
	 **/
	public function GetExerciseTags($exerciseID) {

		$tags = [];

		$query = $this->db->get_where('exercises_tags', ['exerciseID' => $exerciseID]);

		if($query->num_rows() > 0){

			foreach ($query->result() as $tag) {
				$query = $this->db->get_where('tags', ['id' => $tag->tagID]);

				if ($query->num_rows() > 0) {
					$tags[] = $query->result_array()[0];
				}
			}

		}

		return $tags;
	}

	/**
	 * Get path for link (if exercise is linked to other exercise)
	 *
	 * @param int $id Exercise ID
	 *
	 * @return string $linkpath Path to exercise link
	 **/
	public function GetLinkPath($exerciseID) {

		$linkpath = NULL;

		$query = $this->db->get_where('exercises', ['id' => $exerciseID]);

		if($query->num_rows() > 0){

			$exercise = $query->result()[0];

			if ($exercise->link) {
				$linkpath = $exercise->link;
			}
		}

		return $linkpath;
	}

	/**
	 * Get id for linked exercise
	 *
	 * @param int $exerciseID Exercise ID
	 *
	 * @return int $linkID ID of linked exercise
	 **/
	public function GetLinkID($exerciseID) {

		$linkpath 	= $this->GetLinkPath($exerciseID);
		$labels 	= explode("/", $linkpath);

		$classlabel 	= $labels[0];
		$subtopiclabel 	= $labels[1];
		$exerciselabel 	= $labels[2];

		$linkID = $this->ExerciseID($classlabel, $subtopiclabel, $exerciselabel);

		return $linkID;
	}

	/**
	 * Does exercise have link to other exercise
	 *
	 * @param int $id Exercise ID
	 *
	 * @return bool $islinked Whether exercise is linked or not
	 **/
	public function HasLink($exerciseID) {

		$islinked = NULL;

		$query = $this->db->get_where('exercises', ['id' => $exerciseID]);

		if($query->num_rows() > 0){

			$exercise = $query->result()[0];

			if ($exercise->link) {
				$islinked = TRUE;
			}
		}

		return $islinked;
	}

	/**
	 * Get random exercise link
	 *
	 * @param string $classLabe Class label
	 *
	 * @return string $link Exercise link
	 **/
	public function RandomExerciseLink($classLabel=NULL) {

		if ($classLabel) {

			$query = $this->db->query(
				'SELECT * FROM `exercises`
					WHERE `exercises`.`subtopicID` IN (
						SELECT `subtopics`.`id` FROM `subtopics`
							WHERE `subtopics`.`topicID` IN (
								SELECT `topics`.`id` FROM `topics`
									WHERE `topics`.`classID` IN (
										SELECT `classes`.`id` FROM `classes`
											WHERE `classes`.`label` = \''.$classLabel.'\'
										)
								)
						) ORDER BY `exercises`.`difficulty` ASC, RAND();');

		} else {

			$this->db->order_by('difficulty', 'asc');
			$this->db->order_by('id', 'random');
			$query = $this->db->get('exercises');

		}

		if ($query->num_rows() > 0) {

			$id = $query->result()[0]->id;
		
		} else {

			$id = NULL;

		}

		$link = $this->ExerciseLink($id)['link'];

 		return $link;
	}
}

?>