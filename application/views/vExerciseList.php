<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<ul><?php

		foreach ($exercise_list as $exercise) {?>

			<li>
				<a href="<?php echo base_url().'application/setgoal/exercise/'.$exercise['id'];?>">
					<?php echo $exercise['name']; ?>
				</a><?php

					for ($i=0; $i<$exercise['level_max']; $i++) {

						$file = ($i < $exercise['level_user'] ? 'filled' : 'empty');?>

					<img src="<?php echo base_url().'assets/images/star_'.$file.'.png';?>" alt="star" width="15px"><?php

					};?>

			</li><?php

		}?>
		</ul>
	</div>
	<div class="col-md-4"></div>
</div>
