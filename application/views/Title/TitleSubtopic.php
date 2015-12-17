<div class="jumbotron" id="top">
	<h1 class="text-center"><?php	print_r($title); ?></h1>	
	<p class="text-center"><?php echo $subtitle;?></p>
</div>

<div class="row text-center"><?php

	if ($id_next) {?>

	<a class="btn btn-primary" href="<?php echo base_url().'application/setgoal/subtopic/'.$id;?>">
		<span class="glyphicon glyphicon-pencil"></span>&nbsp;
		Témakör gyakorlása
	</a><?php

	}?>

	<a class="btn btn-danger" href="<?php echo base_url().'application/clearresults/'.$id;?>">
		<span class="glyphicon glyphicon-refresh"></span>&nbsp;
		Eredmények törlése
	</a>

	<a class="btn btn-success" data-toggle="modal" data-target="#new">
		<span class="glyphicon glyphicon-plus"></span>&nbsp;
		Új feladat beküldése
	</a>

</div><br />

<div id="new" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Új feladat beküldése</h4>
			</div>
			<div class="modal-body">
				<p>Nem találod valamelyik feladatot? Dobj egy emailt a <b>zsebtanar@gmail.com</b>-ra, és megnézzük, mit tehetünk érted. ;)</p>
			</div>
		</div>
	</div>
</div>