<?php

if ($type == 'exercise') {?>

<h1 class="text-center">
	<?php  echo $title; ?><br />
	<small><?php echo $subtitle; ?></small><br /><?php

	foreach ($img as $index => $star) {?>

	<img id="star<?php echo $index;?>" src="<?php echo base_url().'assets/images/star_'.($star ? 'filled' : 'empty').'.png';?>" alt="star"><?php

	}?>
</h1><br /><?php

} elseif ($type == 'page') {?>

<div class="jumbotron" id="top"><?php echo $img; ?>
	<h1 class="text-center"><?php  echo $title; ?></h1>	
	<p class="text-center"><?php echo $subtitle; ?></p>
</div><?php

	if (isset($id)) {?>

	<div class="row text-center">
		<button class="btn btn-primary" onclick="checkExercises(<?php echo $id;?>, 'normal')">Gyakorlás</button>
	</div><br />

	<div id="restart" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Hoppá!</h4>
				</div>
				<div class="modal-body">
					<p>Már minden feladatot megcsináltál! Szeretnéd újrakezdeni?</p>
					<div class="text-center">
						<button class="btn btn-primary" onclick="checkExercises(<?php echo $id;?>, 'restart')">Igen</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Nem</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>

		// Check solution
		function checkExercises(id, mmethod) {
		    $.ajax({
		        type: "GET",
		        url: "<?php echo base_url();?>application/checkexercises",
		        data: {
		            subtopicID: id,
		            method: mmethod
		        },
		        dataType: "json",
	        	success: function(data) {
	        		if (data['status'] == 'OK') {
	        			window.location = data['href'];
	        		} else {
	        			$('#restart').modal('show');
	        		}
		        }
		    });
	    }
	</script><?php

	}

}?>
