<div class="row">
	<div class="col-md-12 text-center">


		<a class="btn btn-link" href="<?php echo base_url();?>view/main">
			Kezdőlap
		</a><?php

		// print_r($sitemap['quest']);

		if (isset($sitemap['class'])) {?>

			&nbsp;>&nbsp;
			<a class="btn btn-link" href="<?php echo base_url();?>view/main/<?php echo $sitemap['class']['id'];?>">
				<?php print_r($sitemap['class']['name']); ?>
			</a><?php

		}

		if (isset($sitemap['topic'])) {?>

			&nbsp;>&nbsp;
			<a class="btn btn-link" href="<?php echo base_url();?>view/main/<?php echo $sitemap['class']['id'].'/'.$sitemap['topic']['id'];?>">
				<?php print_r($sitemap['topic']['name']); ?>
			</a><?php

		}?>

	</div>
</div>