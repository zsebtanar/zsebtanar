<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center">

		<p>A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény, elsősorban az érettségire készülőknek. Észrevételeket, javaslatokat a <b>zsebtanar@gmail.com</b>-ra lehet küldeni.<br />Jó tanulást! ;)</p>

		<button type="button" class="btn btn-default">Kövess minket <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook</a></b>-on vagy <b><a href="https://www.youtube.com/channel/UCqtj_u2Otbf-9D0sJcb1zMw" target="_blank">Youtube</a></b>-on!</button><br /><br />

		<h2>Gyakorolni szeretnél?</h2>
		<p>Írd be, mit keresel:</p>
		<div class="form-group">
			<input type="text" class="form-control" id="exercise_tags">
		</div>
		<p>Vagy válassz nehézségi szintet:</p><br />
		<div class="row">
			<div class="col-xs-4 text-center">
				<img src="<?php echo base_url();?>assets/images/pawn.svg" alt="pawn"><br />
				<a class="btn btn-lg btn-space btn-success" href="<?php echo $random_exercises['easy']['link'];?>">
					Könnyű&nbsp;
					<span class="glyphicon glyphicon-chevron-right"></span>
				</a>
			</div>
			<div class="col-xs-4 text-center">
				<img src="<?php echo base_url();?>assets/images/knight.svg" alt="knight"><br />
				<a class="btn btn-lg btn-space btn-warning" href="<?php echo $random_exercises['medium']['link'];?>">
					Közepes&nbsp;
					<span class="glyphicon glyphicon-chevron-right"></span>
				</a>
			</div>
			<div class="col-xs-4 text-center">
				<img src="<?php echo base_url();?>assets/images/king.svg" alt="king"><br />
				<a class="btn btn-lg btn-space btn-danger" href="<?php echo $random_exercises['hard']['link'];?>">
					Nehéz&nbsp;
					<span class="glyphicon glyphicon-chevron-right"></span>
				</a>
			</div>
		</div><br /><br /><?php

		$send_exercise = TRUE;		

		if ($send_exercise) {?>

		<p>Nem találod, amit kerestél?</p>

		<a type="button" class="btn btn-info btn-lg" href="http://goo.gl/forms/Kw9aTgyo2h" target="_blank">
			<span class="glyphicon glyphicon-send"></span>&nbsp;
			Küldj be egy feladatot!
		</a><?php

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

										<div id="final_exercises" class="btn-group">
											<a class="btn btn-default btn-lg" href="<?php echo base_url().$class['label'].'/'.$subtopic['label'];?>">
											<?php echo $subtopic['name'];?> <span class="badge"><?php echo $subtopic['exercise_no'];?></span>
											</a>
											<a class="btn btn-default btn-lg" data-toggle="collapse" data-target="#<?php echo $class['label'].$subtopic['label'];?>">
											<span class="caret"></span>
											</a>
										</div><br />

										<div id="<?php echo $class['label'].$subtopic['label'];?>" class="collapse"><?php

											$order = 1;

											foreach ($subtopic['exercises'] as $exercise) {

												if ($exercise['show']) {?>

												<a class="btn btn-default btn-sm row-buttons" href="<?php echo $exercise['link']['link'];?>">
													<?php echo ($exercise['ex_order'] ? $exercise['ex_order'] : $order).'. '.$exercise['name'];?>
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