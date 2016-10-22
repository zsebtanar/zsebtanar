<?php

if ($type == 'main') {?>

<footer class="footer">
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.hu" target="_blank">
		<img alt="Creative Commons Licenc" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" />
	</a>
</footer><?php

}?>

<!-- Auto resize input -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.autosize.input.js"></script><?php

if (base_url() == 'http://zsebtanar.hu/') {?>

<!--Cookie Script-->
<script type="text/javascript" charset="UTF-8" src="<?php echo base_url();?>assets/js/cookie.js"></script><?php

}?>

<script>
	// Close youtube video on collapse close
	$(document).ready(function() { 
		var src = $('.videoplayer').children('iframe').attr('src');

		$('.collapse-button').click(function(e) {
			e.preventDefault();
			$('.videoplayer').children('iframe').attr('src', src);
			$('.modal-background').fadeIn();
		});
	});
</script>