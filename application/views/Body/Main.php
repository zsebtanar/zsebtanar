<div class="row">
	<div class="col-md-12 text-center">
		<a type="button" class="btn btn-success" href="http://goo.gl/forms/Kw9aTgyo2h" target="_blank">
			<span class="glyphicon glyphicon-plus"></span>
			Új feladat beküldése
		</a>
	</div>
</div>
<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-4">
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

							if (count($topic['subtopics']) > 0) {

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
	<div class="col-md-3">
		<div id="latest_exercises" class="panel panel-success">
			<div class="panel-heading small"><b>Legutóbbi feladatok</b></div><?php

		foreach ($latest as $exercise) {?>

			<a class="btn btn-link" href="<?php echo base_url();?>view/exercise/<?php echo $exercise['label'];?>">
					<?php echo $exercise['name'];?>
			</a><br /><?php

		}?>

		</div>
	</div>
	<div class="col-md-2"></div>
</div>