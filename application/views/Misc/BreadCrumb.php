<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<ul class="pager small">
			<li class="previous prev_link">
				<a href="<?php echo $prev['link'];?>" onclick="unsetexercise(event)"><b>
					<span class="glyphicon glyphicon-chevron-left"></span>
					<?php echo $prev['name'];?></b>
				</a>
			</li>
			</li>
			<li class="next next_link">
				<a href="<?php echo $next['link'];?>" onclick="unsetexercise(event)"><b>
					<?php echo $next['name'];?>
					<span class="glyphicon glyphicon-chevron-right"></span></b>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-2"></div>
</div>