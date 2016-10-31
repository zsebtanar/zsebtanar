<div class="row">
	<div class="col-md-6 col-md-offset-3 text-center">
		<div>
			<button data-toggle="collapse" class="btn btn-default" data-target="#about">Mi ez?</button><br/><br/>
		</div>
		<div id="about" class="collapse">
			<div class="well well-sm small">
				A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény videókkal és megoldásokkal, elsősorban az érettségire készülőknek. Észrevételeket, javaslatokat, új feladatokat a <b>zsebtanar@gmail.com</b>-ra lehet küldeni. Ha tetszik az oldal, kövess minket <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook</a></b>-on vagy <b><a href="https://www.youtube.com/channel/UCqtj_u2Otbf-9D0sJcb1zMw" target="_blank">Youtube</a></b>-on!
			</div>
		</div>
		<div class="form-group">
			<input type="text" class="form-control input-lg" id="exercise_tags" placeholder="Válassz témakört!">
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown">
				Kérek egy feladatot! <span class="caret"></span>
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