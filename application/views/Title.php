<?php

if ($type == 'exercise') {?>

<h1 class="text-center">
	<?php  echo $title; ?><br />
	<small><a href="<?php echo base_url().'view/subtopic/'.$subtopicID;?>"><?php echo $subtitle; ?></a></small><br /><?php

	foreach ($img as $key => $value) {?>

	<img id="star<?php echo $key;?>" src="<?php echo base_url().'assets/images/star'.$value.'.png';?>" alt="star"><?php

	}?>
</h1><br /><?php

} elseif ($type == 'subtopic') {?>

<div class="jumbotron" id="top">
	<h1 class="text-center"><?php  echo $title; ?></h1>	
	<p class="text-center"><?php echo $subtitle;?></p>
</div>

<div class="row text-center"><?php

	if ($id_next) {?>

	<a class="btn btn-primary" href="<?php echo base_url().'application/setgoal/subtopic/'.$id;?>">Gyakorlás</a><?php

	}?>

	<a class="btn btn-default" href="<?php echo base_url().'application/clearresults/'.$id;?>">Újrakezd</a>

</div><br /><?php

} elseif ($type == 'main') {?>

<div class="jumbotron" id="top">
	<a href="<?php echo base_url();?>view/subtopic">
		<img class="img-responsive center-block img_main" src="<?php echo base_url();?>assets/images/logo.png" alt="logo" width="150">
	</a>
	<h1 class="text-center">zsebtanár</h1>	
	<p class="text-center">matek | másként</p>
</div><?php

}?>
