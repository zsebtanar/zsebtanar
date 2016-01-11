<div class="jumbotron text-center" id="top">
	<div class="progress">
		<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progress;?>%">
		</div>
	</div>
	<h1><?php  echo $title; ?></h1>
	<p>
		<a href="<?php echo base_url().'view/subtopic/'.$subtopicID.'/'.$questID;?>">
			<?php echo $subtitle;?>
		</a>
	</p><?php

	for ($i=0; $i < $maxlevel; $i++) {?>

		<img id="star<?php echo $i;?>" src="<?php echo base_url().'assets/images/star'.($i < $userlevel ? 1 : 0).'.png';?>" alt="star"><?php

	}?>


</div>