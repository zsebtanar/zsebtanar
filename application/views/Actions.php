<div class="row">
	<div class="btn-group">
		<a href="<?php echo base_url().'view/subtopic';?>" class="btn btn-default">Kezdőlapra</a>
		<a href="<?php echo base_url().'view/activities';?>" class="btn btn-default">Munkamenetek</a>
		<a href="<?php echo base_url().'view/activities/'.$sessionID;?>" class="btn btn-default">Küldetések</a>
		<a href="<?php echo base_url().'application/endsession';?>"class="btn btn-default">Összes törlése</a>
	</div>
</div><br />
<div class="row">
	<h1>
		Feladatok<br />
		<small><?php echo $questName;?></small>
	</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-1">Todo</th>
				<th class="col-md-3">Név</th>
				<th class="col-md-3">Hossz</th>
				<th class="col-md-3">Eredmény</th>
			</tr>
		</thead>
		<tbody><?php

	if (isset($actions)) {
		foreach ($actions as $action) {?>

			<tr>
				<td><?php echo $action['id'];?></td>
				<td><?php

				for ($i=0; $i<$action['todo']; $i++) {?>

					<span class="label label-default">x</span><?php

				}?>
					
				</td>
				<td><?php echo $action['name'];?></td>
				<td>
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $action['length'];?>"
						aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $action['length'];?>%">
							<?php echo $action['length_label'];?>
						</div>
					</div>
				</td>
				<td><?php

				foreach ($action['icons'] as $icon) {?>

					<span class="label label-<?php echo $icon['status'];?>">
						<span class="glyphicon glyphicon-<?php echo $icon['icon'];?>"></span>
					</span><?php

				}?>
					
				</td>
			</tr><?php

		}}?>

		</tbody>
	</table>
</div>