<!DOCTYPE html>
<html lang="hu">
<head><?php

    $this->load->view('Misc/Header'); // FOR ONLINE USE
    // $this->load->view('Misc/Header2'); // FOR OFFLINE USE
    ?>

</head>
<body class="page-<?php echo $type ?>">
<div class="container"><?php

    $debugMode = 0;
    $maindata['debugMode'] = $debugMode;

    $exercise['debugMode'] = $debugMode;

    $this->load->view('Misc/GoogleAnalytics');
    $this->load->view('Misc/NavBar', isset($results) ? array('results' => $results, 'type' => $type) : $maindata);

    if ($type == 'main') {
        $this->load->view('landingPage/Main', $maindata);
    } elseif ($type == 'subtopic' || $type == 'tag') {
        $exercise['$title'] = $title;
        $exercise['breadcrumb'] = $breadcrumb;
        $this->load->view('topics/Main', $exercises);
    } elseif ($type == 'exercise') {
        $exercise['progress'] = $progress;
        $exercise['breadcrumb'] = $breadcrumb;
        $this->load->view('exercise/Main', $exercise);
    } ?>

</div>
<div class="bg-logo">
    <svg xmlns="http://www.w3.org/2000/svg" width="500" viewBox="0 0 130.12 139.45"><path d="M83.2 131.3v-29l.3-22 3.2-2c3-1.5 22-12.4 37.2-21l6-3.4V112l-9.3 5.3c-5 3-15.7 8.8-23.4 13l-14 8v-7.2zM75.4 139.4c-.3 0-.8 0-1-.2-.4 0-1 0-1.6-.2-.7 0-1 0-1-.3l-1-.4s-.3 0-.5-.2l-.6-.3s-.2 0-.3-.2l-.3-.3-.3-.3c0-.2 0-.3-.2-.4-.2 0-.4-.2-.4-.4l-.3-.3-.2-.4c0-.2-.2-.3-.3-.3l-.4-.5c0-.3-.2-.6-.3-.6l-.3-.5-.3-.6h-.4V77.2l.3.4c1 1.3 3 2.5 5.3 3 1.6.4 2 .5 4.4.5 2 0 2.2 0 3.3-.2l2.2-.6 1-.2V95c0 8 0 21-.2 29V139h-.3c-.3.2-1.2.3-2 .3l-.5.2H79h-.5c-.6.2-2.5.3-3 .2z" fill="#4787c5"/><path d="M38 42L15 29l15-8.5L56 6l10.4-6 23.3 13c12.7 7.2 23.2 13 23.2 13.2 0 .3-51.4 28.7-51.7 28.6-.2 0-10.7-5.8-23.4-13zM68.3 68c0-3 0-3-1.4-5.6C66 61 64.7 59 64 58c-1-1-1.7-1.7-1.6-1.8l26-14.5 25.8-14.4 1.2 1.3c.6.7 1.7 2.4 2.4 3.7 1 2.2 1.2 2.7 1.2 5 0 2.4 0 3-1 4.6l-1 1.8-24 13.6L68.4 71s-.2-1.2-.2-3z" fill="#fcd109"/><path d="M25.8 125L0 110.5V84.3c0-21 .2-26.3.6-26L26 72.7 51 87c.3.5.4 6 .3 26.4v25.8L25.7 125zM25.6 70.8C11.6 62.8 0 56 0 55.8c0-1.4 3.2-6.6 5.2-8.3 1-.8 4.2-1.8 6-2 1.8 0 2.2.3 19.3 9.8L55 69c5.7 3.2 7 4 6.5 4.3-3.7 1-7.7 5.4-9.2 10.2l-.8 2-26-14.7z" fill="#ec574c"/></svg>
</div>
<?php

$this->load->view('Modal/Cookie');
$this->load->view('Modal/Info');
$this->load->view('Misc/Footer', array('type' => $type)); ?>

</body>
</html>