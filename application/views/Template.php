<!DOCTYPE html>
<html lang="hu">
	<head><?php

	$this->load->view('Misc/Header'); // FOR ONLINE USE
	// $this->load->view('Misc/Header2'); // FOR OFFLINE USE
	?>

	</head>
<body>
	<div class="container"><?php

	$this->load->view('Misc/GoogleAnalytics');
	$this->load->view('Modal/Cookie');

	if ($type == 'main') {
		
		$this->load->view('Title/Main');
		$this->load->view('Body/Main', $maindata);
		$this->load->view('Misc/NavBar', $maindata);

	} elseif ($type == 'subtopic' || $type == 'tag') {

		$this->load->view('Modal/Info');
		$this->load->view('Misc/BreadCrumb', $breadcrumb);
		$this->load->view('Title/SubtopicTag', $title);
		$this->load->view('Body/SubtopicTag', $exercises);
		$this->load->view('Misc/NavBar', array('results' => $results, 'type' => $type));

	} elseif ($type == 'exercise') {

		$exercise['debugMode'] = 0;

		$this->load->view('Modal/Info');
		$this->load->view('Misc/BreadCrumbExercise', $breadcrumb);
		$this->load->view('Misc/Progress', $progress);
		$this->load->view('Body/Exercise', $exercise);
		$this->load->view('Misc/NavBar', array('results' => $results, 'type' => $type));

	}?>

	</div>

<?php

	$this->load->view('Misc/Footer', array('type' => $type));?>

</body>
</html>