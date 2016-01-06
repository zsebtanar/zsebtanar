<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="panel-group" id="accordion"><?php

		foreach ($quests as $quest) {?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><?php

						if (!$quest['id_next']) {?>

							<img src="<?php echo base_url().'assets/images/tick.png';?>" alt="star" width="40px">&nbsp;<?php

						}?>


						<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $quest['id'];?>">
							<?php echo $quest['name']; ?>
						</a>
					</h4>
				</div><?php

				$class = ($questID == $quest['id'] ? 'in' : '');?>

				<div id="collapse<?php echo $quest['id'];?>" class="panel-collapse collapse <?php echo $class;?>">
					<div class="panel-body">
						<ul><?php

						foreach ($quest['exercises'] as $exercise) {?>

							<li>
								<a href="<?php echo base_url().'application/setgoal/exercise/'.$exercise['id'];?>">
									<?php echo $exercise['name'];?>
								</a>&nbsp;<?php

								foreach ($exercise['levels'] as $level) {?>

									<img src="<?php echo base_url().'assets/images/star'.$level.'.png';?>" alt="star" width="15px"><?php

								}?>

							</li><?php


						}?>

						</ul><?php

						if ($quest['id_next']) {?>

						<div class="text-center">
							<a class="btn btn-primary" href="<?php echo base_url().'application/setgoal/quest/'.$quest['id'];?>">
								Mehet
							</a>
						</div><?php

						} else {?>

						<div class="text-center">
							<a class="btn btn-danger" href="<?php echo base_url().'application/clearresults/'.$subtopicID.'/'.$quest['id'];?>">
								Újrakezd
							</a>
						</div><?php

						}?>

					</div>
				</div>
			</div><?php

		}?>

		</div>
	</div>
	<div class="col-md-3"></div>
</div>