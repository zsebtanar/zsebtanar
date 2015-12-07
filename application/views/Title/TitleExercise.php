<h1 class="text-center">
	<?php  echo $title; ?><br />
	<small><a href="<?php echo base_url().'view/subtopic/'.$subtopicID;?>"><?php echo $subtitle; ?></a></small><br /><?php

	foreach ($img as $key => $value) {?>

	<img id="star<?php echo $key;?>" src="<?php echo base_url().'assets/images/star'.$value.'.png';?>" alt="star"><?php

	}?>
</h1><br />