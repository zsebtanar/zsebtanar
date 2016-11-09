<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Ingyenes matematika oktatóvideók">
<meta name="author" content="Szabó Viktor">
<meta name="google-site-verification" content="zDMT36c6WVy68sDdP-1BBbCVeAYEu2P3RUTXkHyWVDw" />

<meta charset="UTF-8">
<link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/logo.png">

<title>

	Zsebtanár - matek | másként
	
</title>

<!-- Bootstrap CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css"> -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.css"> -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-theme.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap_mod.css">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700&amp;subset=latin,latin-ext">

<!-- JQuery CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">

<!-- JQuery JS -->
<script src="<?php echo base_url();?>assets/js/jquery.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>

<!-- Bootstrap JS -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> -->
<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>

<!-- MathJax JS -->
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
	showProcessingMessages: false,
	tex2jax: {
	  inlineMath: [ ['$','$'], ["\\(","\\)"] ],
	  displayMath: [ ['$$','$$'], ["\\[","\\]"] ]
	},
	"HTML-CSS": { linebreaks: { width: "container" } },
		 "SVG": { linebreaks: { width: "container" } },
	TeX: {extensions: ["color.js"]}
  });
</script>

<script type="text/javascript"
	src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_SVG">
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/svg_mathjax.js"></script>
<script type="text/javascript">
	new Svg_MathJax().install();
</script>

<!-- <script type="text/javascript" src="http://localhost/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script> -->
<!-- <script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script> -->

<!-- Autocomplete -->
<script type="text/javascript">
	$(function(){
		$("#exercise_tags").autocomplete({
			source: "action/gettagexercises", // path to the get_birds method
			select: function( event, ui ) { 
            	window.location.href = ui.item.value;
        	}
		});
	});
</script><?php

if (base_url() == 'http://zsebtanar.hu/') {?>

<!-- Smartlook -->
<script type="text/javascript">
	window.smartlook||(function(d) {
	var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
	var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
	c.charset='utf-8';c.src='//rec.smartlook.com/recorder.js';h.appendChild(c);
	})(document);
	smartlook('init', '3363d8804dbb1cfdd0f93200f91e3e252ff1b25f');
</script><?php

}?>