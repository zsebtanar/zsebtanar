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
	 * Print navbar menu
	 *
	 * @return string $html Html-code
	 */
	public function printNavBarMenu() {

		$classes = $this->db->get('classes');
		$html = '';

		foreach ($classes->result() as $class) {

			$html .= '<li>'."\n";
			$html .= "\t\t\t\t\t".'<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
			$html .= $class->name."\n";

			$topics = $this->db->get_where('topics', array('classID' => $class->id));

			if (count($topics) > 0) {

				$html .= "\t\t\t\t\t\t".'<b class="caret"></b>'."\n";
				$html .= "\t\t\t\t\t".'</a>'."\n";
				$html .= "\t\t\t\t\t".'<ul class="dropdown-menu multi-level">'."\n";


				foreach ($topics->result() as $topic) {

					$html .= $this->printNavBarMenuTopic($topic);

				}

				$html .= "\t\t\t\t\t".'</ul>'."\n";
			}
			$html .= "\t\t\t\t".'</li>'."\n";
		}

		return $html;
	}

	/**
	 * Print navbar menu for topic
	 *
	 * @param array  $topic Topic data
	 * @return string $html Html-code
	 */
	public function printNavBarMenuTopic($topic) {

		$html = '';

		$html .= "\t\t\t\t\t\t".'<li class="dropdown-submenu">'."\n";
		$html .= "\t\t\t\t\t\t\t".'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'."\n";
		$html .= "\t\t\t\t\t\t\t\t".$topic->name."\n";
		$html .= "\t\t\t\t\t\t\t".'</a>'."\n";

		$subtopics = $this->db->get_where('subtopics', array('topicID' => $topic->id));

		if (count($subtopics) > 0) {

			$html .= "\t\t\t\t\t\t\t".'<ul class="dropdown-menu">'."\n";

			foreach ($subtopics->result() as $subtopic) {

				$html .= "\t\t\t\t\t\t\t\t".'<li>'."\n";
				$html .= "\t\t\t\t\t\t\t\t\t".'<a href="'.base_url().'page/view/'.$subtopic->id.'">'."\n";
				$html .= "\t\t\t\t\t\t\t\t\t\t".$subtopic->name."\n";
				$html .= "\t\t\t\t\t\t\t\t\t".'</a>'."\n";
				$html .= "\t\t\t\t\t\t\t\t".'</li>'."\n";
			}

			$html .= "\t\t\t\t\t\t\t".'</ul>'."\n";
		}

		$html .= "\t\t\t\t\t\t".'</li>'."\n";

		return $html;
	}

	/**
	 * Print refresh icon
	 *
	 * @param  int $id    Subtopic ID
	 * @param  int $level Excercise level
	 * @return string $html Html-code
	 */
	public function printRefreshIcon($id=NULL, $level=NULL) {

		

		$href = base_url().'update/database/'.$id.($id ? '/' : '').$level;
		
		$html = '';

		if ($this->session->userdata('Logged_in')) {

			$html = '<li>'."\n";
			$html = "\t".'<a href="'.$href.'">'."\n";
			$html = "\t\t".'<span class="glyphicon glyphicon-refresh"></span> Frissítés'."\n";
			$html = "\t".'</a>'."\n";
			$html = '</li>'."\n";

		}

		return $html;
	}

	/**
	 * Print page title
	 *
	 * @param  int $id    Subtopic ID
	 * @return string $html Html-code
	 */
	public function printPageTitle($id=NULL) {

		if ($id) {

			$subtopics = $this->db->get_where('subtopics', array('id' => $id));
			$subtopic = $subtopics->result()[0];
			$classes = $this->db->get_where('classes', array('id' => $subtopic->classID));
			$class = $classes->result()[0];

			$title = $subtopic->name;
			$subtitle = $class->name;
			$img = '';

		} else {

			$title = 'zsebtanár';
			$subtitle = 'matek | másként';
			$img = '<a href="page/view"><img class="img-responsive center-block" '
				.'src="'.base_url().'assets/images/logo.png" alt="logo" width="150"></a>';
				
		}

		return array(
			'img' => $img,
			'title' => $title,
			'subtitle' => $subtitle,
		);
	}
}

?>