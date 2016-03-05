<nav class="navbar navbar-default navbar-fixed-top" role="banner">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="<?php echo base_url().'view/main/';?>">
				<img src="<?php echo base_url();?>assets/images/logo_small.png" alt="logo" width="20">
			</a>

			<a class="navbar-brand navbar-logo" href="<?php echo base_url().'view/main/';?>">
				<b>Zsebtanár</b>
			</a>
			&nbsp;&nbsp;

			<a href="#" class="navbar-brand" data-toggle="modal" data-target="#result_symbols">
				<img src="<?php echo base_url();?>assets/images/trophy.png" alt="shield" width="17">
			</a>

			<a href="#" class="navbar-brand" data-toggle="modal" data-target="#result_symbols"><b class="results"><?php echo $trophies;?></b></a>

			<a href="#" class="navbar-brand" data-toggle="modal" data-target="#result_symbols">
				<img src="<?php echo base_url();?>assets/images/shield.png" alt="shield" width="15">
			</a>

			<a href="#" class="navbar-brand" data-toggle="modal" data-target="#result_symbols"><b class="results"><?php echo $shields;?></b></a>

			<a href="#" class="navbar-brand" data-toggle="modal" data-target="#result_symbols">
				<img src="<?php echo base_url();?>assets/images/coin.png" alt="coin" width="15">
			</a>

			<a href="#" class="navbar-brand" data-toggle="modal" data-target="#result_symbols"><b class="results"><?php echo $points;?></b></a>

		</div>

		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right small"><?php

			if ($this->Session->CheckLogin()) {?>

				<li>
					<a href="<?php echo base_url().'application/clearresults/'.
						(isset($type) ? $type : '').
						(isset($id) ? '/'.$id : '');?>">
						<span class="glyphicon glyphicon-remove"></span>&nbsp;Pontok törlése
					</a>
				</li>
				<li>
					<a href="<?php echo base_url().'update/database/'.
						(isset($type) ? $type : '').'/'.
						(isset($id) ? '/'.$id : '');?>">
						<span class="glyphicon glyphicon-refresh"></span>&nbsp;Adatbázis frissítése
					</a>
				</li>
				<li>
					<a href="<?php echo base_url().'application/logout';?>">
						<span class="glyphicon glyphicon-log-out"></span>&nbsp;Kijelentkezés
					</a>
				</li><?php

			} else {?>

				<li>
					<a href="#" data-toggle="modal" data-target="#info">
						<span class="glyphicon glyphicon-info-sign"></span>&nbsp;Mi ez?
					</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#login">
						<span class="glyphicon glyphicon-user"></span>&nbsp;Admin
					</a>
				</li><?php

			}?>
			
			</ul>
		</div>
</nav>
