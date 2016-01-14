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
		<div class="panel-group" id="accordion"><?php

		if (is_array($quests)) {

			foreach ($quests as $quest) {?>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><?php

							if ($quest['complete']) {?>

								<img src="<?php echo base_url().'assets/images/tick.png';?>" alt="star" width="20px">&nbsp;<?php

							} else {?>

								<img src="<?php echo base_url().'assets/images/tick_empty.png';?>" alt="star" width="20px">&nbsp;<?php

							}?>


							<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $quest['id'];?>">
								<?php echo $quest['name']; ?>
							</a>
						</h4>
					</div><?php

					$class = ($questID == $quest['id'] ? 'in' : '');
					$class = 'in';
					?>

					<div id="collapse<?php echo $quest['id'];?>" class="panel-collapse collapse <?php echo $class;?>">
						<div class="panel-body">
							<ul><?php

							foreach ($quest['exercises'] as $exercise) {?>

								<li>
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

								<div class="text-center"><?php

								if (count($quest['links']) == 1) {?>

									<br />Túl nehéz? Először nézd meg ezt:<br /><?php

								} else {?>

									<br />Túl nehéz? Először nézd meg ezeket:<br /><?php

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
					</div>
				</div><?php

			}

		}?>

		</div>
	</div>
	<div class="col-md-3"></div>
</div>