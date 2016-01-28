<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="progress">
			<div id="progress_bar" class="progress-bar progress-bar-<?php print_r($current['progress']['style']);?> progress-bar-striped active" role="progressbar" aria-valuenow="<?php print_r($current['progress']['value']);?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $current['progress']['value'];?>%">
			</div>
		</div>
	</div>
	<div class="col-md-3"></div>
</div>

