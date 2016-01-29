<ol class="breadcrumb text-center small">
	<li>
		<a href="<?php echo $link_prev;?>">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
	</li>
	<li>
		<a href="<?php echo base_url();?>view/main">
			Kezdőlap
		</a>
	</li><?php

	if (isset($breadcrumb['class'])) {?>

		<li>
			<a href="<?php echo base_url();?>view/main/<?php echo $breadcrumb['class']['id'];?>">
				<?php print_r($breadcrumb['class']['name']); ?>
			</a>
		</li><?php

	}

	if (isset($breadcrumb['topic'])) {?>

		<li>
			<a href="<?php echo base_url();?>view/main/<?php echo $breadcrumb['class']['id'].'/'.$breadcrumb['topic']['id'];?>">
				<?php print_r($breadcrumb['topic']['name']); ?>
			</a>
		</li><?php

	}

	if (isset($breadcrumb['quest'])) {?>

		<li>
			<a href="<?php echo base_url();?>view/subtopic/<?php echo $breadcrumb['subtopic']['id'].'/'.$breadcrumb['quest']['id'];?>">
				<?php print_r($breadcrumb['quest']['name']); ?>
			</a>
		</li><?php

	}?>

	<li>
		<a href="<?php echo $link_next;?>">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</a>
	</li>
</ol>