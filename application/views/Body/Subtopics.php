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
		</div><?php

		foreach ($html as $class => $topics) {?>

			<h1><?php echo $class;?></h1><?php

			if (count($topics) > 0) {?>

				<div class="panel-group" id="accordion"><?php

					$i = 0;

					foreach ($topics as $topic => $subtopics) {?>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>">
									<?php echo $topic;?>
								</a>
							</h4>
						</div><?php

						if (count($subtopics) > 0) {?>

						<div id="collapse<?php echo $i;?>" class="panel-collapse collapse in">
							<div class="panel-body">

								<ul><?php

								foreach ($subtopics as $subtopic_id => $subtopic_name) {?>

									<li>
										<a href="<?php echo base_url();?>view/subtopic/<?php echo $subtopic_id;?>">
											<?php echo $subtopic_name;?>
										</a>
									</li><?php

								}?>

								</ul>
							</div>
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