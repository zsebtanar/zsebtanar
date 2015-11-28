<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<ul><?php

		foreach ($exercise_list as $exercise) {?>

			<li>
				<a href="<?php echo base_url();?>view/exercise?id=<?php echo $exercise['id'];?>&amp;method=exercise">
					<?php echo $exercise['name']; ?>
				</a> (<?php echo $exercise['level_user'].'/'.$exercise['level_max'];?>)
			</li><?php

		}?>
		</ul>
	</div>
	<div class="col-md-4"></div>
</div>
