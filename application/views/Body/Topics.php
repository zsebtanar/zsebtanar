<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="text-center alert alert-warning small">
			<b>Figyelem!</b> A honlap tesztüzemben működik. Bármilyen észrevételt a <b>zsebtanar@gmail.com</b>-ra lehet küldeni.
		</div>
		<!--<div class="text-center">
			<a href="#" class="btn btn-class openall">mutat</a>&nbsp;|&nbsp;
			<a href="#" class="btn btn-class closeall">elrejt</a>
		</div>--><?php

		foreach ($html as $class => $topics) {?>

			<h1><?php echo $class;?></h1><?php

			if (count($topics) > 0) {?>

				<div class="panel-group" role="tablist"><?php

					foreach ($topics as $topic => $subtopics) {

						if (isset($subtopics['topicID'])) {?>

					<div class="panel panel-<?php echo ($topicID == $subtopics['topicID'] ? 'primary' : 'default');?>">
						<div class="panel-heading" role="tab" id="heading<?php echo $subtopics['topicID'];?>">
							<h4 class="panel-title">
								<a class="" role="button" data-toggle="collapse" href="#listgroup<?php echo $subtopics['topicID'];?>" aria-expanded="true" aria-controls="listgroup<?php echo $subtopics['topicID'];?>">
									<?php echo $topic;?>
								</a>
							</h4>
						</div><?php

						if (count($subtopics) > 0) {?>

						<div id="listgroup<?php echo $subtopics['topicID'];?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $subtopics['topicID'];?>" aria-expanded="true">
							<ul class="list-group"><?php

								foreach ($subtopics as $subtopic_id => $subtopic) {

									if (isset($subtopic['name'])) {?>

									<li class="list-group-item">
										<a href="<?php echo base_url();?>view/subtopic/<?php echo $subtopic_id;?>">
											<?php echo $subtopic['name'];?>
										</a><?php

										if ($subtopic['iscomplete']) {?>

											&nbsp;<img src="<?php echo base_url().'assets/images/trophy.png';?>" alt="star" width="20px"><?php

										}?>
										
									</li><?php

									}

								}?>

							</ul>
						</div><?php

						}?>

					</div><?php

						}

					}?>

				</div><?php
			}
		}?>

	</div>
	<div class="col-md-3"></div>
</div>