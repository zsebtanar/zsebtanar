<?php

if ($type == 'exercise') {?>

<h1 class="text-center">
	<?php  echo $title; ?><br />
	<small><a href="<?php echo $href;?>"><?php echo $subtitle; ?></a></small><br /><?php

	foreach ($img as $index => $star) {?>

	<img id="star<?php echo $index;?>" src="<?php echo base_url().'assets/images/star_'.($star ? 'filled' : 'empty').'.png';?>" alt="star"><?php

	}?>
</h1><br /><?php

} elseif ($type == 'subtopic') {?>

<div class="jumbotron" id="top"><?php echo $img; ?>
	<h1 class="text-center"><?php  echo $title; ?></h1>	
	<p class="text-center"><?php echo $subtitle;?></p>
</div><?php

	if ($id_next) {?>

		<div class="row text-center">
			<a class="btn btn-primary" href="<?php echo base_url().'view/exercise/'.$id_next;?>">Gyakorlás</a>
		</div><br /><?php

	} else {?>

		<div class="row text-center">
			<a class="btn btn-primary" href="<?php echo base_url().'application/clearresults/'.$id;?>">Újrakezd</a>
		</div><br /><?php

	}
}?>
