<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="progress"><?php

			if ($current['level_user'] == 0) {
				$style = 'info';
			} elseif ($current['level_user'] == 1) {
				$style = 'warning';
			} else {
				$style = 'danger';
			}?>

			<div class="progress-bar progress-bar-<?php echo $style;?> progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $current['progress'];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $current['progress'];?>%">
			</div>
		</div>
	</div>
	<div class="col-md-3"></div>
</div>

