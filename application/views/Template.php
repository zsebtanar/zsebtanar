<!DOCTYPE html>
<html lang="hu">
	<head><?php

	$this->load->view('Misc/Header');?>

	</head>
<body>
	<div class="container"><?php

	$this->load->view('Misc/GoogleAnalytics');

	$this->load->view('Modal/Info');?>

	<nav class="navbar navbar-default navbar-fixed-top" role="banner"><?php

		$this->load->view('Misc/NavBar');?>

	</nav><?php

	if ($type == 'main') {

		$this->load->view('Title/Main');
		$this->load->view('Body/Subtopics', $menu);

	} elseif ($type == 'subtopic') {

		$this->load->view('Title/Subtopic', $titledata);
		$this->load->view('Body/Quests', $quests);

	} elseif ($type == 'exercise') {

		$this->load->view('Title/Exercise', $titledata);
		$this->load->view('Body/Exercise', $exercise);

	}?>

	</div><?php

	$this->load->view('Misc/Footer');?>

</body>
</html>