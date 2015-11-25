<!DOCTYPE html>
<html lang="hu">

	<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="Ingyenes matematika oktatóvideók">
<meta name="author" content="Szabó Viktor">
<link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/logo_small.png">

<title>

	Zsebtanár - matek | másként
	
</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap_mod.css">

<!-- Typeahead CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/typeahead.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">

<!-- JQuery JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

<!-- Typeahead JS -->
<script>
    $(document).ready(function() {
        $("#search").keyup(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>application/search",
                data: {
                    keyword: $("#search").val()
                },
                dataType: "json",
                success: function(data) {
                    $('#DropdownExercises').empty();
                    if (data.length > 0) {
                        $('#search').attr("data-toggle", "dropdown");
                        $('#DropdownExercises').dropdown('toggle');
                    } else if (data.length == 0) {
                        $('#search').attr("data-toggle", "");
                    }
                    $.each(data, function(key, value) {
                        if (data.length >= 0)
                            $('#DropdownExercises').append('<li role="presentation"><a href="<?php echo base_url();?>view/exercise/' + value['id'] + '">' + value['name'] + '</a></li>');
                    });
                }
            });
        });
        $('ul.exercises').on('click', 'li a', function() {
            $('#search').val($(this).text());
            ($(this).getAttribute('href'));
        });
    });
</script>

<!-- Query parser for youtube links JS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-queryParser.js"></script>

<!-- MathJax JS -->
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
	showProcessingMessages: false,
	tex2jax: {
	  inlineMath: [ ['$','$'], ["\\(","\\)"] ],
	  displayMath: [ ['$$','$$'], ["\\[","\\]"] ]
	},
	"HTML-CSS": { linebreaks: { automatic: true } },
		 "SVG": { linebreaks: { automatic: true } },
	TeX: {extensions: ["color.js"]}
  });
</script>
<script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
</head>

<body>
<div class="container">