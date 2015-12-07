<div class="container-fluid">

	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<a class="navbar-brand" href="<?php echo base_url();?>view/subtopic">
			<img src="<?php echo base_url();?>assets/images/logo_small.png" alt="logo" width="20">
		</a>
		<a class="navbar-brand" href="<?php echo base_url();?>view/subtopic">Zsebtanár</a>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav"><?php

			foreach ($html as $class => $topics) {?>

				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php

					echo $class;

				if (count($topics) > 0) {?>

						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu multi-level"><?php

					foreach ($topics as $topic => $subtopics) {?>

						<li class="dropdown-submenu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php

						echo $topic;?>

							</a><?php

						if (count($subtopics) > 0) {?>

							<ul class="dropdown-menu"><?php

							foreach ($subtopics as $subtopic_id => $subtopic_name) {?>

								<li>
									<a href="<?php echo base_url();?>view/subtopic/<?php echo $subtopic_id;?>">
										<?php echo $subtopic_name;?>
									</a>
								</li><?php

							}?>

							</ul><?php

						}?>

						</li><?php

					}?>

					</ul><?php

				} else {?>

					</a><?php

				}?>

				</li><?php

			}

			?>
		</ul>
		<ul class="nav navbar-nav navbar-right"><?php

		if (NULL !== $this->session->userdata('Logged_in') &&
			$this->session->userdata('Logged_in')) {?>

			<li>
				<a href="<?php echo base_url().'update/database/';?>">
					<span class="glyphicon glyphicon-refresh"></span> Frissítés
				</a>
			</li>
			<li>
				<a href="<?php echo base_url().'view/activities/';?>">
					<span class="glyphicon glyphicon-menu-hamburger"></span> Tevékenységek
				</a>
			</li>
			<li>
				<a href="<?php echo base_url().'application/logout';?>">
					<span class="glyphicon glyphicon-log-out"></span> Kijelentkezés
				</a>
			</li><?php

		} else {?>

			<li>
				<a href="#" data-toggle="modal" data-target="#login">
					<span class="glyphicon glyphicon-user"></span> Admin
				</a>
			</li><?php

		}?>

			<li>
				<a href="#" data-toggle="modal" data-target="#info">
					<span class="glyphicon glyphicon-info-sign"></span> Mi ez?
				</a>
			</li>
		</ul>
	</div>
</div>
