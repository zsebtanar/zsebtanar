<!DOCTYPE html>
<html lang="hu">
	<head><?php

	$this->load->view('Misc/Header');?>

	</head>
<body>
	<div class="container"><?php

	$this->load->view('Misc/GoogleAnalytics');

	$this->load->view('Modal/Info');
	$this->load->view('Modal/Login');
	$this->load->view('Modal/Cookie');

	$this->load->view('Misc/NavBar', $results);

	if ($type == 'main') {
		
		$this->load->view('Title/Main');
		$this->load->view('Body/Main', $maindata);

	} elseif ($type == 'subtopic') {


		$this->load->view('Misc/BreadCrumb', $breadcrumb);
		$this->load->view('Title/Subtopic', $title);
		$this->load->view('Body/Subtopic', $exercises);

	} elseif ($type == 'exercise') {

		$this->load->view('Misc/BreadCrumb', $breadcrumb);
		$this->load->view('Misc/Progress', $progress);
		$this->load->view('Body/Exercise', $exercise);

	}?>

	</div><?php

	$this->load->view('Misc/Footer');?>

</body>
</html>