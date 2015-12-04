<div class="row">
	<div class="btn-group">
		<a href="<?php echo base_url().'view/subtopic';?>" class="btn btn-default">Kezdőlapra</a>
		<a href="<?php echo base_url().'application/deletesessions';?>"class="btn btn-default">Összes törlése</a>
	</div>
</div><br />
<div class="row">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Idő</th>
			</tr>
		</thead>
		<tbody><?php

		if (isset($sessions)) {
			foreach ($sessions as $session) {?>

			<tr>
				<td><?php echo $session['id'];?></td>
				<td>
					<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $session['progress'];?>"
						aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $session['progress'];?>%">
							<?php echo $session['time'];?>
						</div>
					</div>
				</td>
			</tr><?php

			}
		}?>

		</tbody>
	</table>
</div>