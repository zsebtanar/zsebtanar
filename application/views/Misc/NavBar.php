<nav class="navbar navbar-default navbar-fixed-top" role="banner">
	<div class="container-fluid">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="<?php echo base_url();?>view/main">
				<span class="glyphicon glyphicon-home"></span>&nbsp;Kezdőlap
			</a>

			<a class="navbar-brand" href="#">
				<img src="<?php echo base_url();?>assets/images/trophy.png" alt="shield" width="17">
			</a>

			<a class="navbar-brand" href="#"><b class="results"><?php echo $subtopics;?></b></a>

			<a class="navbar-brand" href="#">
				<img src="<?php echo base_url();?>assets/images/shield.png" alt="shield" width="15">
			</a>

			<a class="navbar-brand" href="#"><b class="results"><?php echo $quests;?></b></a>

			<a class="navbar-brand" href="#">
				<img src="<?php echo base_url();?>assets/images/coin.png" alt="coin" width="15">
			</a>

			<a class="navbar-brand" href="#"><b class="results"><?php echo $points;?></b></a>

		</div>

		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right"><?php

			if (NULL !== $this->session->userdata('Logged_in') &&
				$this->session->userdata('Logged_in')) {?>

				<li>
					<a href="<?php echo base_url().'application/clearresults/'.
						(isset($type) ? $type : '').
						(isset($id) ? '/'.$id : '');?>">
						<span class="glyphicon glyphicon-remove"></span> Törlés
					</a>
				</li>
				<li>
					<a href="<?php echo base_url().'update/database/'.
						(isset($type) ? $type : '').'/'.
						(isset($id) ? '/'.$id : '');?>">
						<span class="glyphicon glyphicon-refresh"></span> Frissítés
					</a>
				</li>
				<li>
					<a href="<?php echo base_url().'application/logout';?>">
						<span class="glyphicon glyphicon-log-out"></span> Kijelentkezés
					</a>
				</li><?php

			} else {?>

				<li>
					<a href="#" data-toggle="modal" data-target="#info">
						<span class="glyphicon glyphicon-info-sign"></span>
					</a>
				</li><?php

			}?>
			
			</ul>
		</div>
	</div>
</nav>
