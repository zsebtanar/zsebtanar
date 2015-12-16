<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="text-center">
			<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<?php echo $className;?>&nbsp;<span class="caret"></span>
				</button>
				<input type="hidden" name="classID" value="<?php echo $classID;?>">
				<ul class="dropdown-menu" role="menu"><?php

					foreach ($classes as $class) {?>

					<li>
						<a href="<?php echo base_url().'view/main/'.$class['id'];?>">
							<?php echo $class['name'];?>
						</a>
					</li><?php

					}?>

				</ul>
			</div><?php

			if ($topics) {?>

			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<?php echo $topicName;?>&nbsp;<span class="caret"></span>
				</button>
				<input type="hidden" name="topicID" value="<?php echo $topicID;?>">
				<ul class="dropdown-menu" role="menu"><?php

					foreach ($topics as $topic) {?>

					<li>
						<a href="<?php echo base_url().'view/main/'.$class['id'].'/'.$topic['id'];?>">
							<?php echo $topic['name'];?>
						</a>
					</li><?php

					}?>

				</ul>
			</div><?php

			}?>

		</div>
		<br />
		<div class="typeahead-container">
			<div class="typeahead-field">
				<span class="typeahead-query">
					<input id="search" name="search" type="search" autofocus autocomplete="off">
				</span>
			</div>
		</div>
		<ul class="dropdown-menu exercises" style="margin-top:-45px;margin-left:15px;margin-right:0px;" role="menu" aria-labelledby="dropdownMenu" id="DropdownExercises"></ul>
	</div>
	<div class="col-md-4"></div>
</div>