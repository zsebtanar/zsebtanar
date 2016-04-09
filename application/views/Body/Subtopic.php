<?php

if (is_array($exercises)) {

	$order = 1;

	foreach ($exercises as $exercise) {?>

	<div class="row">
		<div class="col-md-3 text-right">
			<h1 class="exercise-label">
				<?php echo $order;?>
			</h1>
		</div>
		<div class="col-md-6">
			<img id="star1" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][0].'.png';?>" alt="star"  width="15px">
			<img id="star2" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][1].'.png';?>" alt="star"  width="15px">
			<img id="star3" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][2].'.png';?>" alt="star"  width="15px"><br />
			<?php


			echo $exercise['question'];?>

			<div class="text-center exercise_button">
				<a class="btn btn-primary" href="<?php echo base_url().'view/exercise/'.$exercise['label'];?>">
					Mehet&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>
				</a><?php

				if ($this->Session->CheckLogin()) {

					if ($exercise['status'] == 'OK') {?>

					<span class="label label-success">
						<span class="glyphicon glyphicon-ok"></span>
					</span><?php

					} else {?>

					<span class="label label-warning">
						<span class="glyphicon glyphicon-remove"></span>
					</span><?php

					}
				}?>

			</div>


		</div>
		<div class="col-md-3"></div>
	</div><?php

		$order++;

	}

}?>