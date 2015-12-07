<div class="jumbotron" id="top">
	<h1 class="text-center"><?php  echo $title; ?></h1>	
	<p class="text-center"><?php echo $subtitle;?></p>
</div>

<div class="row text-center"><?php

	if ($id_next) {?>

	<a class="btn btn-primary" href="<?php echo base_url().'application/setgoal/subtopic/'.$id;?>">Gyakorlás</a><?php

	}?>

	<a class="btn btn-default" href="<?php echo base_url().'application/clearresults/'.$id;?>">Újrakezd</a>

</div><br />