<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center">
		<p>A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény, elsősorban az érettségire készülőknek. Észrevételeket, javaslatokat a <b>zsebtanar@gmail.com</b>-ra, vagy a <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook-oldalra</a></b> lehet küldeni. Jó tanulást!</p><?php

		$send_exercise = FALSE;?>

		<a type="button" class="btn btn-success <?php echo ($send_exercise ? '' : 'disabled');?> btn-space-big btn-lg" href="http://goo.gl/forms/Kw9aTgyo2h" target="_blank">
			<span class="glyphicon glyphicon-plus"></span>
			Új érettségi feladat beküldése
		</a><?php

		if (!$send_exercise) {?>

		<div id="send_explanation" class="text-center small">
			<a class="btn btn-link" href="#" data-toggle="modal" data-target="#send_explanation_modal">
				Miért nem tudok feladatot beküldeni?
			</a>
		</div><?php

		}?>

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

										<div class="btn-group">
											<a class="btn btn-default btn-lg" href="<?php echo base_url().$class['label'].'/'.$subtopic['label'];?>">
											<?php echo $subtopic['name'];?> <span class="badge"><?php echo $subtopic['exercise_no'];?></span>
											</a>
											<a class="btn btn-default btn-lg" data-toggle="collapse" data-target="#<?php echo $class['label'].$subtopic['label'];?>">
											<span class="caret"></span>
											</a>
										</div>

										<div id="<?php echo $class['label'].$subtopic['label'];?>" class="collapse"><?php

											$order = 1;

											foreach ($subtopic['exercises'] as $exercise) {

												if ($exercise['show']) {?>

												<a class="btn btn-default btn-sm row-buttons" href="<?php echo base_url().$class['label'].'/'.$subtopic['label'].'/'.$exercise['label'];?>">
													<?php echo $order.'. '.$exercise['name'];?>
												</a><?php

												}

												$order++;
												
											}?>

										</div><?php

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
										<?php echo $subtopic['name'];?> <span class="badge"><?php echo $subtopic['exercise_no'];?></span>
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