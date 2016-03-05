<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<!-- <div class="text-center alert alert-warning small">
			<b>Figyelem!</b> A honlap tesztüzemben működik. Bármilyen észrevételt a <b>zsebtanar@gmail.com</b>-ra lehet küldeni.
		</div>
		<br />
		<div class="text-center">
			<a href="#" class="btn btn-class openall">mutat</a>&nbsp;|&nbsp;
			<a href="#" class="btn btn-class closeall">elrejt</a>
		</div> --><?php

		foreach ($classes as $class) {

			if ($class['show']) {?>

			<h1><?php echo $class['name'];?></h1><?php

			if (count($class['topics']) > 0) {

				foreach ($class['topics'] as $topic) {

					if ($topic['show']) {?>

						<h3><?php echo $topic['name'];?></h3>
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-10"><?php

							if (count($subtopics) > 0) {

								foreach ($topic['subtopics'] as $subtopic) {

									if ($subtopic['show']) {?>

									<a class="btn btn-link" href="<?php echo base_url();?>view/subtopic/<?php echo $subtopic['id'];?>">
											<?php echo $subtopic['name'];?>
									</a><br /><?php

									}

								}?>

							</div>
						</div><?php

						}
					}
				}
			}

			}

		}?>

	</div>
	<div class="col-md-3"></div>
</div>