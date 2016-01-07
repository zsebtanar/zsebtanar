<div class="jumbotron text-center" id="top">
	<h1><?php  echo $title; ?></h1>
	<p>
		<a href="<?php echo base_url().'view/subtopic/'.$subtopicID.'/'.$questID;?>">
			<?php echo $subtitle;?>
		</a>
	</p><?php

	foreach ($levels as $key => $value) {?>

	<img id="star<?php echo $key;?>" src="<?php echo base_url().'assets/images/star'.$value.'.png';?>" alt="star"><?php

	}?>
</div>