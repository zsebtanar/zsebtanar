<!-- Auto resize input -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.autosize.input.js"></script><?php

if ($type == 'main' && base_url() == 'http://zsebtanar.hu/') {?>

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