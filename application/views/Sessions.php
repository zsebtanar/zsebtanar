<div class="row">
	<div class="btn-group">
		<a href="<?php echo base_url().'view/subtopic';?>" class="btn btn-default">Kezdőlapra</a>
		<a href="<?php echo base_url().'application/endsession';?>"class="btn btn-default">Összes törlése</a>
	</div>
</div><br />
<div class="row">
	<h1>Munkamenetek</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-2">Kezdés</th>
				<th class="col-md-5">Hossz</th>
				<th class="col-md-4">Küldetések</th>
			</tr>
		</thead>
		<tbody><?php

		if (isset($sessions)) {
			foreach ($sessions as $session) {?>

			<tr>
				<td><a href="<?php echo base_url().'view/activities/'.$session['id'];?>"><?php echo $session['id'];?></a></td>
				<td><?php echo $session['start'];?></td>
				<td>
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $session['length'];?>"
						aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $session['length'];?>%">
							<?php echo $session['length_label'];?>
						</div>
					</div>
				</td>
				<td>
					<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $session['quests1'];?>%">
							<?php echo $session['quests1_label'];?>
						</div>
						<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $session['quests2'];?>%">
							<?php echo $session['quests2_label'];?>
						</div>
					</div>
				</td>
			</tr><?php

			}
		}?>

		</tbody>
	</table>
</div>