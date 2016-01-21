<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="text-center alert-warning">
			<p>
				<b>Figyelem!</b><br /> A honlap tesztüzemben működik, ezért előfordulhatnak hibák. Az is megeshet, hogy egy feladat túl könnyű vagy túl nehéz. Ilyenkor bármilyen észrevételt a <b>zsebtanar@gmail.com</b>-ra lehet küldeni.
			</p>
		</div>
		<div class="text-center">
			<a href="#" class="btn btn-class openall">
				<img src="<?php echo base_url();?>assets/images/eye_open.png" alt="logo" width="20">
			</a>
			<a href="#" class="btn btn-class closeall">
				<img src="<?php echo base_url();?>assets/images/eye_close.png" alt="logo" width="20">
			</a>
		</div><?php

		foreach ($html as $class => $topics) {?>

			<h1><?php echo $class;?></h1><?php

			if (count($topics) > 0) {?>

				<div class="panel-group" role="tablist"><?php

					$i = 0;

					foreach ($topics as $topic => $subtopics) {?>

					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?php echo $i;?>">
							<h4 class="panel-title">
								<a class="" role="button" data-toggle="collapse" href="#listgroup<?php echo $i;?>" aria-expanded="true" aria-controls="listgroup<?php echo $i;?>">
									<?php echo $topic;?>
								</a>
							</h4>
						</div><?php

						if (count($subtopics) > 0) {?>

						<div id="listgroup<?php echo $i;?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $i;?>" aria-expanded="true">
							<ul class="list-group"><?php

								foreach ($subtopics as $subtopic_id => $subtopic) {?>

									<li class="list-group-item">
										<a href="<?php echo base_url();?>view/subtopic/<?php echo $subtopic_id;?>">
											<?php echo $subtopic['name'];?>
										</a><?php

										if ($subtopic['iscomplete']) {?>

											&nbsp;<img src="<?php echo base_url().'assets/images/trophy.png';?>" alt="star" width="20px"><?php

										}?>
										
									</li><?php

								}?>

							</ul>
						</div><?php

						}?>

					</div><?php

					$i++;

					}?>

				</div><?php
			}
		}?>

	</div>
	<div class="col-md-3"></div>
</div>