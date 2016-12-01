<div class="row">
	<div class="col-md-6 col-md-offset-3 text-center">
		<br />
		<p class="small">
			A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény videókkal és megoldásokkal, elsősorban az érettségire készülőknek. Észrevételeket, javaslatokat, új feladatokat a <b>zsebtanar@gmail.com</b>-ra lehet küldeni. Ha tetszik az oldal, kövess minket <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook</a></b>-on vagy <b><a href="https://www.youtube.com/channel/UCqtj_u2Otbf-9D0sJcb1zMw" target="_blank">Youtube</a></b>-on!
		</p>
		<br />

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
		</div>

	</div>
</div>