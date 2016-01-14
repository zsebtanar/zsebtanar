<div class="jumbotron text-center" id="top">
	<h1><?php

		if ($prev) {?>

		<a href="<?php echo base_url().'view/subtopic/'.$prev['id'];?>"><?php

		} else {?>

		<a href="<?php echo base_url().'view/main/';?>"><?php

		}?>

		<span class="title-icon glyphicon glyphicon-chevron-left"></span></a>&nbsp;<?php

		echo $current['title'].'&nbsp;';

		if ($next) {?>

		<a href="<?php echo base_url().'view/subtopic/'.$next['id'];?>"><?php

		} else {?>

		<a href="<?php echo base_url().'view/main/';?>"><?php

		}?>

		<span class="title-icon glyphicon glyphicon-chevron-right"></span></a>
	</h1>	
	<p>
		<a href="<?php echo base_url().'view/main/';?>">
			<?php echo $current['subtitle'];?>
		</a>
	</p>
</div>
