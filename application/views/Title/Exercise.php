<div class="jumbotron text-center" id="top">
	<div class="progress">
		<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $current['progress'];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $current['progress'];?>%">
		</div>
	</div>
	<h1><?php

		if ($prev) {?>

		<a href="<?php echo base_url().'view/exercise/'.$prev['id'];?>"><?php

		} else {?>

		<a href="<?php echo base_url().'view/main/';?>"><?php

		}?>

		<span class="title-icon glyphicon glyphicon-chevron-left"></span></a>&nbsp;<?php

		echo $current['title'].'&nbsp;';

		if ($next) {?>

		<a href="<?php echo base_url().'view/exercise/'.$next['id'];?>"><?php

		} else {?>

		<a href="<?php echo base_url().'view/main/';?>"><?php

		}?>

		<span class="title-icon glyphicon glyphicon-chevron-right"></span></a>
	</h1>

	<p>
		<a href="<?php echo base_url().'view/subtopic/'.$current['subtopicID'].'/'.$current['questID'];?>">
			<?php echo $current['subtitle'];?>
		</a>
	</p><?php

	for ($i=0; $i < $current['level_max']; $i++) {?>

		<img id="star<?php echo $i;?>" src="<?php echo base_url().'assets/images/star'.($i < $current['level_user'] ? 1 : 0).'.png';?>" alt="star"><?php

	}?>


</div>