<div class="row">
	<div class="col-md-6 col-md-offset-3 text-center">
		<div>
			<button data-toggle="collapse" class="btn btn-link" data-target="#about">
				<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>&nbsp;Mi ez?
			</button><br/><br/>
		</div>
		<div id="about" class="collapse">
			<div class="well well-sm small">
				A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény videókkal és megoldásokkal, elsősorban az érettségire készülőknek. Észrevételeket, javaslatokat, új feladatokat a <b>zsebtanar@gmail.com</b>-ra lehet küldeni. Ha tetszik az oldal, kövess minket <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook</a></b>-on vagy <b><a href="https://www.youtube.com/channel/UCqtj_u2Otbf-9D0sJcb1zMw" target="_blank">Youtube</a></b>-on!
			</div>
		</div><?php

		if (isset($_SESSION['show_search_message']) && $_SESSION['show_search_message']) {?>

			<div class="alert alert-danger text-center small">
				<b>Hoppá... </b>Úgy tűnik, valami hiba történt. Megpróbálnád még egyszer?
			</div><?php

			$_SESSION['show_search_message'] = FALSE;

		}

		// unset($_SESSION['first_search_done']);

		if (!isset($_SESSION['first_search_done']) || $_SESSION['first_search_done']==FALSE) {?>

		<div class="input-group">
			<input type="text" id="search_tag" class="form-control input-lg" placeholder="Mit szeretnél gyakorolni?">
			<span class="input-group-btn">
				<button id="search_button" class="btn btn-default btn-lg" type="button">
					<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;
					<span class="search-title">Keresés</span>
				</button>
			</span>
		</div><br /><?php

		} else {			

			?>

			<input type="text" id="exercise_tags" class="form-control input-lg" placeholder="Mit szeretnél gyakorolni?"><br />

			<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>&nbsp;Véletlen feladat <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu"><?php
					foreach ($classes as $class) {?>

					 	<li>
					 		<a href="action/getrandomexercise/<?php echo $class['label'];?>">
					 			<?php echo $class['name'];?>
					 		</a>
					 	</li><?php
						
					}?>
				</ul>
			</div><?php

		}?>

	</div>
</div>