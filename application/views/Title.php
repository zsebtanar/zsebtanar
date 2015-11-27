<?php

if ($type == 'exercise') {?>

<h1 class="text-center">
	<?php  echo $title; ?><br />
	<small><?php echo $subtitle; ?></small>
</h1><br /><?php

} elseif ($type == 'page') {?>

<div class="jumbotron" id="top"><?php echo $img; ?>
	<h1 class="text-center"><?php  echo $title; ?></h1>	
	<p class="text-center"><?php echo $subtitle; ?></p>
</div><?php

}?>
