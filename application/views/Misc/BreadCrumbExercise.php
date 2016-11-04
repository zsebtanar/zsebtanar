<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="row">
			<div class="col-xs-4"><?php

				if (count($easier) > 0) {?>

				<ul class="pager small dropdown">
					<li class="previous prev_link">

						<a href="#" onclick="unsetexercise(event)" data-toggle="dropdown"><b>
							<span class="glyphicon glyphicon-chevron-left"></span>
							<span class="breadcumb-title">Könnyebb</span></b>
						</a>

						<ul class="dropdown-menu dropdown-menu-left"><?php

								foreach ($easier as $exercise) {?>
							<li class="dropdown-menu-li">
								<a href="<?php echo $exercise['link'];?>">
									<img class="star" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][0].'.png';?>" alt="star"  width="15px">
									<img class="star" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][1].'.png';?>" alt="star"  width="15px">
									<img class="star star-last" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][2].'.png';?>" alt="star"  width="15px">
									<?php echo $exercise['name'];?>
								</a>
							</li><?php

							}?>

						</ul>

					</li>
				</ul><?php

				}?>

			</div>
			<div class="col-xs-4">
				<ul class="pager small text-center">
					<li class="prev_link">
						<a href="<?php echo $random;?>" onclick="unsetexercise(event)"><b>
							<span class="glyphicon glyphicon-refresh"></span>
							<span class="breadcumb-title">Véletlen</span></b>
						</a>
					</li>
				</ul>
			</div>
			<div class="col-xs-4"><?php

				if (count($harder) > 0) {?>

				<ul class="pager small dropdown">
					<li class="next next_link">

						<a href="#" onclick="unsetexercise(event)" data-toggle="dropdown"><b>
							<span class="breadcumb-title">Nehezebb</span>
							<span class="glyphicon glyphicon-chevron-right"></span></b>
						</a>

						<ul class="dropdown-menu dropdown-menu-right"><?php

								foreach ($harder as $exercise) {?>
							<li class="dropdown-menu-li">
								<a href="<?php echo $exercise['link'];?>">
									<img class="star" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][0].'.png';?>" alt="star"  width="15px">
									<img class="star" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][1].'.png';?>" alt="star"  width="15px">
									<img class="star star-last" src="<?php echo base_url().'assets/images/star'.$exercise['progress']['stars'][2].'.png';?>" alt="star"  width="15px">
									<?php echo $exercise['name'];?>
								</a>
							</li><?php

							}?>

						</ul>

					</li>
				</ul><?php

				}?>

			</div>
		</div>			
	</div>
</div>