<!DOCTYPE html>
<html lang="hu">
	<head><?php

	$this->load->view('Header');?>

	</head>
<body>
	<div class="container"><?php

	$this->load->view('GoogleAnalytics');

	$this->load->view('Modal/Info');
	$this->load->view('Modal/Youtube');
	$this->load->view('Modal/Login');?>

	<nav class="navbar navbar-default navbar-fixed-top" role="banner"><?php

		$this->load->view('NavBar', $menu);?>

	</nav><?php

	if ($type == 'main') {

		$this->load->view('Title/TitleMain');
		$this->load->view('Search');

	} elseif ($type == 'subtopic') {

		$this->load->view('Title/TitleSubtopic', $title);
		$this->load->view('ExerciseList', $exercises);

	} elseif ($type == 'exercise') {

		$this->load->view('Title/TitleExercise', $title);
		$this->load->view('Exercise', $exercise);

	}?>

	</div><?php

	$this->load->view('Footer');?>

</body>
</html>