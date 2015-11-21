<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<ul><?php

		foreach ($exercise_list as $exercise) {?>

			<li>
				<a href="<?php echo base_url();?>view/exercise/<?php echo $exercise['id'];?>">
					<?php echo $exercise['name']; ?>
				</a>
			</li><?php

		}?>
		</ul>
	</div>
	<div class="col-md-4"></div>
</div>
