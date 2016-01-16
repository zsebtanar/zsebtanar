<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="text-center">
			<a href="#" class="btn btn-class openall">
				<img src="<?php echo base_url();?>assets/images/eye_open.png" alt="logo" width="20">
			</a>
			<a href="#" class="btn btn-class closeall">
				<img src="<?php echo base_url();?>assets/images/eye_close.png" alt="logo" width="20">
			</a>
		</div><br /><br /><br />
		<div class="panel-group" role="tablist"><?php

		if (is_array($quests)) {

				foreach ($quests as $quest) {?>

				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading<?php echo $quest['id'];?>">
						<h4 class="panel-title">
							<a class="" role="button" data-toggle="collapse" href="#listgroup<?php echo $quest['id'];?>" aria-expanded="true" aria-controls="listgroup<?php echo $quest['id'];?>">
								<?php echo $quest['name']; ?>
							</a><?php

							if ($quest['complete']) {?>

								&nbsp;<img src="<?php echo base_url().'assets/images/tick.png';?>" alt="star" width="20px">&nbsp;<?php

							}?>

						</h4>
					</div><?php

					$class = ($questID == $quest['id'] ? 'in' : '');
					$class = 'in';
					?>

					<div id="listgroup<?php echo $quest['id'];?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $quest['id'];?>" aria-expanded="true">
						<ul class="list-group"><?php

						foreach ($quest['exercises'] as $exercise) {?>

							<li class="list-group-item">
								<a href="<?php echo base_url().'view/exercise/'.$exercise['id'];?>">
									<?php echo $exercise['name'];?>
								</a>&nbsp;<?php

								for ($i=0; $i < $exercise['maxlevel']; $i++) {?>

									<img id="star<?php echo $i;?>" src="<?php echo base_url().'assets/images/star'.($i < $exercise['userlevel'] ? 1 : 0).'.png';?>" alt="star"  width="15px"><?php

								}?>

							</li><?php


						}?>

						</ul><?php

						if (count($quest['links']) > 0) {?>

							<div class="panel-footer"><?php

							if (count($quest['links']) == 1) {?>

								Túl nehéz? Először nézd meg ezt:&nbsp;<?php

							} else {?>

								Túl nehéz? Először nézd meg ezeket:&nbsp;<?php

							}

							foreach ($quest['links'] as $link) {?>

								<a class="btn btn-default" href="<?php echo base_url().'view/subtopic/'.$link['subtopicID'].'/'.$link['questID'];?>"><?php

									if ($link['complete']) {?>

										<img src="<?php echo base_url().'assets/images/tick_grey.png';?>" alt="star" width="20px">&nbsp;<?php

									}

									echo $link['name'];?>

								</a>&nbsp;<?php

							}?>

							</div><?php

						}?>

					</div>
				</div><?php

			}

		}?>

		</div>
	</div>
	<div class="col-md-3"></div>
</div>