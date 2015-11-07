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

			<a class="navbar-brand" href="index.php">
				<img src="assets/images/logo_small.png" alt="logo" width="20">
			</a>
			<a class="navbar-brand" href="index.php">Zsebtanár</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php

				printNavBarMenu();

				?></ul>
			<ul class="nav navbar-nav navbar-right">
				<?php

				if ($_SESSION['logged_in']) {?>

				<li>
					<a href="<?php echo $href; ?>">
						<span class="glyphicon glyphicon-refresh"></span> Frissítés
					</a>
				</li>
				<?php		  

				}

				?><li>
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