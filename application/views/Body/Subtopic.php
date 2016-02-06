<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8"><br />
		<div class="text-center">
			<a href="#" class="btn btn-class openall">mutat</a>&nbsp;|&nbsp;
			<a href="#" class="btn btn-class closeall">elrejt</a>
		</div>
		<div class="panel-group" role="tablist"><?php

		if (is_array($exercises)) {

			$order = 1;

			foreach ($exercises as $exercise) {?>

			<div class="panel panel-default">
				<div class="panel-heading panel-heading-sm clearfix" role="tab" id="heading<?php echo $exercise['id'];?>">
					<a class="panel-heading-title" role="button" data-toggle="collapse" href="#listgroup<?php echo $exercise['id'];?>" aria-expanded="true" aria-controls="listgroup<?php echo $exercise['id'];?>">
						<?php echo $order;?>. feladat
					</a>&nbsp;

					<img id="star1" src="<?php echo base_url().'assets/images/star'.(33 <= $exercise['progress']['value'] ? 1 : 0).'.png';?>" alt="star"  width="15px">
					<img id="star2" src="<?php echo base_url().'assets/images/star'.(66 <= $exercise['progress']['value'] ? 1 : 0).'.png';?>" alt="star"  width="15px">
					<img id="star3" src="<?php echo base_url().'assets/images/star'.(100 <= $exercise['progress']['value'] ? 1 : 0).'.png';?>" alt="star"  width="15px">

					<a class="btn btn-primary btn-md pull-right" href="<?php echo base_url().'view/exercise/'.$exercise['id'];?>">
						Mehet&nbsp;
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
				</div>

				<div id="listgroup<?php echo $exercise['id'];?>" class="panel-collapse panel-body collapse <?php echo $exercise['class'];?>" role="tabpanel" aria-labelledby="heading<?php echo $exercise['id'];?>" aria-expanded="true"><?php

					echo $exercise['question'];?>

				</div>
			</div><br /><?php

			$order++;

			}

		}?>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>