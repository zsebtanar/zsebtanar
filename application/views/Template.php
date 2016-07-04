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

	if ($type == 'main') {
		
		$this->load->view('Misc/NavBar', array('results' => $results, 'type' => $type));
		$this->load->view('Title/Main');
		$this->load->view('Body/Main', $maindata);

	} elseif ($type == 'subtopic') {

		$this->load->view('Misc/NavBar', array('results' => $results, 'type' => $type));
		$this->load->view('Misc/BreadCrumb', $breadcrumb);
		$this->load->view('Title/Subtopic', $title);
		$this->load->view('Body/Subtopic', $exercises);

	} elseif ($type == 'exercise') {

		$this->load->view('Misc/NavBar', array('results' => $results, 'type' => $type, 'hash' => $exercise['hash']));
		$this->load->view('Misc/BreadCrumb', $breadcrumb, array('hash' => $exercise['hash']));
		$this->load->view('Misc/Progress', $progress);
		$this->load->view('Body/Exercise', $exercise);

	}?>

	</div>

<?php

	$this->load->view('Misc/Footer', array('type' => $type));?>

</body>
</html>