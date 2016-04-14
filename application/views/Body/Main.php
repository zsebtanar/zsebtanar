<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center">
		<p>A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény, elsősorban az érettségire készülőknek. Észrevételeket, javaslatokat a <b>zsebtanar@gmail.com</b>-ra, vagy a <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook-oldalra</a></b> lehet küldeni. Jó tanulást!</p>
		<a type="button" class="btn btn-success btn-space-big btn-lg" href="http://goo.gl/forms/Kw9aTgyo2h" target="_blank">
			<span class="glyphicon glyphicon-plus"></span>
			Új érettségi feladat beküldése
		</a>
	</div>
	<div class="col-md-3"></div>
</div>
<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">

		<div class="text-center">
			<h1>Érettségi feladatok</h1><?php

			foreach ($classes as $class) {

				if ($class['show'] && $class['label'] == 12) {

					if (count($class['topics']) > 0) {

						foreach ($class['topics'] as $topic) {

							if ($topic['show'] && $topic['name'] == 'Érettségi') {

								if (count($topic['subtopics']) > 0) {

									foreach ($topic['subtopics'] as $subtopic) {

										if ($subtopic['show']) {?>

										<a class="btn btn-default btn-lg" href="<?php echo base_url().$class['label'].'/'.$subtopic['label'];?>">
											<?php echo $subtopic['name'];?> <span class="badge"><?php echo $subtopic['exercises'];?></span>
										</a><br /><?php

										}

									}
								}

							}
						}
					}?>
						</div>
					</div><?php

				}

			}?>

		</div>


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

				<div class="row exercises-all">
					<div class="col-sm-5">
						<h1 class="class-label text-right">
							<?php echo $class['label'];?>
						</h1>
					</div>
					<div class="col-sm-7"><?php

				if (count($class['topics']) > 0) {

					foreach ($class['topics'] as $topic) {

						if ($topic['show'] && $topic['name'] != 'Érettségi') {?>

							<h3><?php echo $topic['name'];?></h3><?php

							if (count($topic['subtopics']) > 0) {

								foreach ($topic['subtopics'] as $subtopic) {

									if ($subtopic['show']) {?>

									<a class="btn btn-link btn-lg" href="<?php echo base_url().$class['label'].'/'.$subtopic['label'];?>">
										<?php echo $subtopic['name'];?> <span class="badge"><?php echo $subtopic['exercises'];?></span>
									</a><br /><?php

									}

								}
							}

						}
					}
				}?>
					</div>
				</div><?php

			}

		}?>

	</div>
	<div class="col-md-3">
	</div>
</div>