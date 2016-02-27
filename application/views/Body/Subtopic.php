<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8"><br />
		<div class="text-center">
			<a href="#" class="btn btn-class openall">mutat</a>&nbsp;|&nbsp;
			<a href="#" class="btn btn-class closeall">elrejt</a>
		</div>
		<div class="panel-group" id="accordion"><?php

		if (is_array($exercises)) {

			$order = 1;

			foreach ($exercises as $exercise) {?>

			<div class="panel panel-default">
				<div class="panel-heading panel-heading-sm clearfix" id="heading<?php echo $exercise['id'];?>">
					<b>
						<a class="panel-heading-title" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $exercise['id'];?>" aria-expanded="true" aria-controls="collapse<?php echo $exercise['id'];?>">
							<?php echo $order;?>. feladat
						</a>
					</b>&nbsp;

					<img id="star1" src="<?php echo base_url().'assets/images/star'.(33 <= $exercise['progress']['value'] ? 1 : 0).'.png';?>" alt="star"  width="15px">
					<img id="star2" src="<?php echo base_url().'assets/images/star'.(66 <= $exercise['progress']['value'] ? 1 : 0).'.png';?>" alt="star"  width="15px">
					<img id="star3" src="<?php echo base_url().'assets/images/star'.(100 <= $exercise['progress']['value'] ? 1 : 0).'.png';?>" alt="star"  width="15px">

					<a class="btn btn-primary btn-md pull-right btn-exercise-start" href="<?php echo base_url().'view/exercise/'.$exercise['id'];?>">Mehet&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a><?php

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

					}?>

				</div>

				<div id="collapse<?php echo $exercise['id'];?>" class="panel-collapse collapse <?php echo $exercise['class'];?>">
					<div class="panel-body"><?php

						echo $exercise['question'];?>

					</div>
				</div>
			</div><?php

			$order++;

			}

		}?>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>