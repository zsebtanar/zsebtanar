<!DOCTYPE html>
<html lang="hu">
	<head><?php

	$this->load->view('Header');?>

	</head>
<body>
	<div class="container"><?php

	if (in_array($type, ['main', 'subtopic', 'exercise'])) {

	$this->load->view('GoogleAnalytics');

	$this->load->view('Modal/Info');
	$this->load->view('Modal/Youtube');
	$this->load->view('Modal/Login');?>

	<nav class="navbar navbar-default navbar-fixed-top" role="banner"><?php

		$this->load->view('NavBar2');
		// $this->load->view('NavBar', $menu);
		?>

	</nav><?php

	$this->load->view('Title/Title'.$type, $title);

	}

	if ($type == 'main') {

		// $this->load->view('Search', $search);
		$this->load->view('TOC', $menu);

	} elseif ($type == 'subtopic') {

		$this->load->view('ExerciseList', $exercises);

	} elseif ($type == 'exercise') {

		$this->load->view('Exercise', $exercise);

	} else {

		$this->load->view('Activities/'.$type, $data);

	}?>

	</div><?php

	$this->load->view('Footer');?>

</body>
</html>