<ol class="breadcrumb text-center">


		<li>
			<a href="<?php echo base_url();?>view/main">
				Kezdőlap
			</a>
		</li><?php

		// print_r($sitemap['quest']);

		if (isset($sitemap['class'])) {?>

			<li>
				<a href="<?php echo base_url();?>view/main/<?php echo $sitemap['class']['id'];?>">
					<?php print_r($sitemap['class']['name']); ?>
				</a>
			</li><?php

		}

		if (isset($sitemap['topic'])) {?>

			<li>
				<a href="<?php echo base_url();?>view/main/<?php echo $sitemap['class']['id'].'/'.$sitemap['topic']['id'];?>">
					<?php print_r($sitemap['topic']['name']); ?>
				</a>
			</li><?php

		}?>

</ol>