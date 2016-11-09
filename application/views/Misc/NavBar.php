<nav class="navbar navbar-inverse navbar-fixed-top" role="banner">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="<?php echo base_url();?>" onclick="unsetexercise(event)">
				<img src="<?php echo base_url();?>assets/images/logo.svg" alt="logo" width="20">
			</a>

			<a class="navbar-brand navbar-logo" href="<?php echo base_url();?>" onclick="unsetexercise(event)">
				<b>Zsebtanár</b>
			</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right small"><?php

			if ($type != 'main') {?>

				<li>
					<a href="#" data-toggle="modal" data-target="#info">
						<img src="<?php echo base_url();?>assets/images/trophy.png" alt="shield" width="17">&nbsp;

						<b><span class="trophies"><?php echo $results['trophies'];?></span></b>&nbsp;&nbsp;

						<img src="<?php echo base_url();?>assets/images/shield.png" alt="shield" width="15">&nbsp;

						<b><span class="shields"><?php echo $results['shields'];?></span></b>&nbsp;&nbsp;

						<img src="<?php echo base_url();?>assets/images/coin.png" alt="coin" width="15">&nbsp;

						<b><span class="points"><?php echo $results['points'];?></span></b>

						&nbsp;&nbsp;<span class="glyphicon glyphicon-question-sign"></span>
					</a>
				</li><?php

			} else {?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle navbar-primary" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<b><?php echo $final_exercises['name'];?><span class="caret"></span></b>
					<ul class="dropdown-menu"><?php

					foreach ($final_exercises['subtopics'] as $subtopic) {?>
			
					<li>
						<a href="<?php echo base_url().$final_exercises['classlabel'].'/'.$subtopic['label'];?>">
							<?php echo $subtopic['name'];?>
						</a>
					</li><?php

					}?>

			 		</ul>
			 		</a>
				</li><?php

				foreach ($classes as $class) {?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<b><?php echo str_replace("osztály", "o.", $class['name']);?></b><span class="caret"></span>
					<ul class="dropdown-menu"><?php

					if (count($class['topics']) > 0) {
						foreach ($class['topics'] as $topic) {?>

							<li class="dropdown-header"><?php echo $topic['name'];?></li><?php

							foreach ($topic['subtopics'] as $subtopic) {?>
					
							<li>
								<a href="<?php echo base_url().$class['label'].'/'.$subtopic['label'];?>" class="navbar-submenuitem">
									<?php echo $subtopic['name'];?>
								</a>
							</li><?php

							}
						}
					}?>

			 		</ul>
			 		</a>
				</li><?php

				}

			}?>
			
			</ul>
		</div>
</nav>