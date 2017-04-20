<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<ul class="pager">
			<li class="previous prev_link">
				<a href="<?php echo $prev['link'];?>" onclick="unsetexercise(event)">
					<i class="fa fa-chevron-left"></i>
					<?php echo $prev['name'];?>
				</a>
			</li>
			</li>
			<li class="next next_link">
				<a href="<?php echo $next['link'];?>" onclick="unsetexercise(event)">
					<?php echo $next['name'];?>
					<i class="fa fa-chevron-right"></i>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-2"></div>
</div>