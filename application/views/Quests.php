<div class="row">
	<div class="btn-group">
		<a href="<?php echo base_url().'view/subtopic';?>" class="btn btn-default">Kezdőlapra</a>
		<a href="<?php echo base_url().'view/activities';?>" class="btn btn-default">Munkamenetek</a>
		<a href="<?php echo base_url().'application/endsession';?>"class="btn btn-default">Összes törlése</a>
	</div>
</div><br />
<div class="row">
	<h1>
		Küldetések<br />
		<small><?php echo $sessionID;?>. munkamenet</small>
	</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-1">Típus</th>
				<th class="col-md-1">Osztály</th>
				<th class="col-md-3">Név</th>
				<th class="col-md-3">Hossz</th>
				<th class="col-md-3">Feladatok</th>
			</tr>
		</thead>
		<tbody><?php

	if (isset($quests)) {
		foreach ($quests as $quest) {?>

			<tr>
				<td>
					<a href="<?php echo base_url().'view/activities/'.$sessionID.'/'.$quest['id'];?>">
						<?php echo $quest['id'];?>
					</a>
				</td>
				<td>
					<span class="label label-<?php echo $quest['status'];?>">
						<?php echo $quest['method'];?>
					</span>
				</td>
				<td><?php echo $quest['class'];?></td>
				<td><?php echo $quest['name'];?></td>
				<td>
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $quest['length'];?>"
						aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $quest['length'];?>%">
							<?php echo $quest['length_label'];?>
						</div>
					</div>
				</td>
				<td>
					<div class="progress">
						<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $quest['actions1'];?>%">
							<?php echo $quest['actions1_label'];?>
						</div>
						<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $quest['actions2'];?>%">
							<?php echo $quest['actions2_label'];?>
						</div>
						<div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $quest['actions3'];?>%">
							<?php echo $quest['actions3_label'];?>
						</div>
					</div>
				</td>
			</tr><?php

		}}?>

		</tbody>
	</table>
</div>