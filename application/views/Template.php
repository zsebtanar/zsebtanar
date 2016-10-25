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

	$this->load->view('Modal/Info');
	$this->load->view('Modal/Login');
	$this->load->view('Modal/Cookie');
	
	$this->load->view('Misc/NavBar', array('results' => $results, 'type' => $type));

	if ($type == 'main') {
		
		$this->load->view('Title/Main');
		$this->load->view('Body/Main', $maindata);

	} elseif ($type == 'subtopic') {

		$this->load->view('Misc/BreadCrumb', $breadcrumb);
		$this->load->view('Title/Subtopic', $title);
		$this->load->view('Body/Subtopic', $exercises);

	} elseif ($type == 'tag') {

		$this->load->view('Title/Tag', $title);
		$this->load->view('Body/Tag', $exercises);

	} elseif ($type == 'exercise') {

		$this->load->view('Misc/BreadCrumb', $breadcrumb);
		$this->load->view('Misc/Progress', $progress);
		$this->load->view('Body/Exercise', $exercise);

	} elseif ($type == 'stat_users') {

		$this->load->view('Body/StatisticsUsers');

	} elseif ($type == 'stat_exercises') {

		$this->load->view('Body/StatisticsExercises');

	}?>

	</div>

<?php

	$this->load->view('Misc/Footer', array('type' => $type));?>

</body>
</html>