<ol class="breadcrumb text-center">


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

		}?>

</ol>