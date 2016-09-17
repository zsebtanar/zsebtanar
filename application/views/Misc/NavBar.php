<nav class="navbar navbar-default navbar-fixed-top" role="banner">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="<?php echo base_url();?>" onclick="unsetexercise(event)">
				<img src="<?php echo base_url();?>assets/images/logo_small.png" alt="logo" width="20">
			</a>

			<a class="navbar-brand navbar-logo" href="<?php echo base_url();?>" onclick="unsetexercise(event)">
				<b>Zsebtanár</b>
			</a>
		</div>

		<div class="collapse navbar-collapse">
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

			}

			if ($type == 'main') {
				if ($this->Session->CheckLogin()) {?>

					<li>
						<a href="<?php echo base_url();?>view/statistics/">
							<span class="glyphicon glyphicon-signal"></span>&nbsp;Elemzés
						</a>
					</li>
					<li>
						<a href="<?php echo base_url();?>action/clearresults/">
							<span class="glyphicon glyphicon-remove"></span>&nbsp;Pontok törlése
						</a>
					</li>
					<li>
						<a href="<?php echo base_url();?>action/update/">
							<span class="glyphicon glyphicon-refresh"></span>&nbsp;Adatbázis frissítése
						</a>
					</li>
					<li>
						<a href="<?php echo base_url();?>action/logout/">
							<span class="glyphicon glyphicon-log-out"></span>&nbsp;Kijelentkezés
						</a>
					</li><?php

				} else {?>

					<li>
						<a href="#" data-toggle="modal" data-target="#login">
							<span class="glyphicon glyphicon-user"></span>&nbsp;Belépés
						</a>
					</li><?php

				}

			}?>
			
			</ul>
		</div>
</nav>

<script>
	function clearpoints(event){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>action/clearresults/"
		});
		location.reload();
	}
	function update(event){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>action/update/"
		});
		location.reload();
	}
	function logout(event){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>action/logout/"
		});
		location.reload();
	}
</script>