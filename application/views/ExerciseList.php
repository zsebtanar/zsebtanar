<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="text-right">
			<a href="#" class="btn btn-class openall">mindet kinyit</a>|
			<a href="#" class="btn btn-class closeall">mindet becsuk</a>
		</div>
		<div class="panel-group" id="accordion"><?php

		foreach ($exercise_list as $exercise) {?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $exercise['id'];?>">
							<?php echo $exercise['name']; ?>
						</a>&nbsp;<?php

						foreach ($exercise['levels'] as $level) {?>

						<img src="<?php echo base_url().'assets/images/star'.$level.'.png';?>" alt="star" width="15px"><?php

						}?>


					</h4>
				</div>
				<div id="collapse<?php echo $exercise['id'];?>" class="panel-collapse collapse">
					<div class="panel-body">
						<?php echo $exercise['question']; ?>

						<div class="pull-right">
							<br />
							<a class="btn btn-info" href="<?php echo base_url().'application/setgoal/exercise/'.$exercise['id'];?>">
								<span class="glyphicon glyphicon-chevron-right"></span>
							</a>
						</div>

					</div>
				</div>
			</div><?php

		}?>

		</div>
	</div>
	<div class="col-md-3"></div>
</div>


<script>
$('.closeall').click(function(){
  $('.panel-collapse.in')
    .collapse('hide');
});
$('.openall').click(function(){
  $('.panel-collapse:not(".in")')
    .collapse('show');
});
</script>