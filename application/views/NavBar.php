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

			<a class="navbar-brand" href="<?php echo base_url();?>page/view">
				<img src="<?php echo base_url();?>assets/images/logo_small.png" alt="logo" width="20">
			</a>
			<a class="navbar-brand" href="<?php echo base_url();?>page/view">Zsebtanár</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php

				echo $html;

				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php

				echo $refresh_icon;

				?>
				<li>
					<a href="#" data-toggle="modal" data-target="#search">
						<span class="glyphicon glyphicon-search"></span> Keresés
					</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#info">
						<span class="glyphicon glyphicon-info-sign"></span> Mi ez?
					</a>
				</li>
			</ul>
		</div>
	</div>
</nav>