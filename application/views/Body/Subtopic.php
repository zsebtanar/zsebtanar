<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8"><br />
		<div class="text-center">
			<a href="#" class="btn btn-class openall">mutat</a>&nbsp;|&nbsp;
			<a href="#" class="btn btn-class closeall">elrejt</a>
		</div><?php

		if (is_array($exercises)) {

			$order = 1;

			foreach ($exercises as $exercise) {?>

					<img id="star1" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][0].'.png';?>" alt="star"  width="15px">
					<img id="star2" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][1].'.png';?>" alt="star"  width="15px">
					<img id="star3" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][2].'.png';?>" alt="star"  width="15px"><br /><?php

					if (isset($exercise['hint']) && $exercise['hint']) {?>

						<a href="#" class="pull-right" data-toggle="modal" data-target="#exercise_symbols">
							<img src="<?php echo base_url();?>assets/images/light_bulb.png" alt="hint" width="25px">
						</a><?php

					}

					if (isset($exercise['youtube']) && $exercise['youtube']) {?>

						<a href="#" class="pull-right" data-toggle="modal" data-target="#exercise_symbols">
							<img src="<?php echo base_url();?>assets/images/play.png" alt="hint" width="25px">
						</a><?php

					}

					if (isset($exercise['explanation']) && $exercise['explanation']) {?>

						<a href="#" class="pull-right" data-toggle="modal" data-target="#exercise_symbols">
							<img src="<?php echo base_url();?>assets/images/buoy.png" alt="hint" width="25px">
						</a><?php

					}

					echo $order.'. '.$exercise['question'];?>

					<div class="text-center">
						<a class="btn btn-primary" href="<?php echo base_url().'view/exercise/'.$exercise['id'];?>">
							Mehet&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
					</div><?php

			$order++;

			}

		}?>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>