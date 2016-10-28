<?php

if (is_array($exercises)) {

	$order = 1;

	foreach ($exercises as $ex) {?>

	<div class="row">
		<div class="col-md-3 text-right">
			<h1 class="exercise-label">
				<?php echo $order;?>
			</h1>
		</div>
		<div class="col-md-6">
			<img id="star1" src="<?php echo base_url().'assets/images/star'.$ex['progress']['stars'][0].'.png';?>" alt="star"  width="15px">
			<img id="star2" src="<?php echo base_url().'assets/images/star'.$ex['progress']['stars'][1].'.png';?>" alt="star"  width="15px">
			<img id="star3" src="<?php echo base_url().'assets/images/star'.$ex['progress']['stars'][2].'.png';?>" alt="star"  width="15px"><br />
			<?php

			if (array_key_exists('classlabel', $ex) &&
				array_key_exists('subtopiclabel', $ex) &&
				array_key_exists('subtopicname', $ex)) {?>

				<p>
					<a class="label label-warning" href="<?php echo base_url().$ex['classlabel'].'/'.$ex['subtopiclabel'];?>">
						<?php echo $ex['classlabel'].'/'.$ex['subtopicname'];?>
					</a>
				</p><?php

			}

			if (count($ex['tags']) > 0) {?>
		
				<p class="small">
					<span class="glyphicon glyphicon-tags"></span>&nbsp;

					<?php

					foreach ($ex['tags'] as $key => $tag) {?>

						<a class="label label-default" href="<?php echo base_url().'view/tag/'.$tag['label'];?>"><?php echo $tag['name'];?></a><?php

					}?>

				</p><?php

			}

			echo $ex['question'];?>

			<div class="text-center exercise_button">
				<a class="btn btn-primary" href="<?php print_r($ex['link']['link']);?>">
					Mehet&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>
				</a><?php

				if ($this->Session->CheckLogin()) {

					if ($ex['status'] == 'OK') {?>

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