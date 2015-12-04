<div class="row">
	<div class="btn-group">
		<a href="<?php echo base_url().'view/subtopic';?>" class="btn btn-default">Kezdőlapra</a>
		<a href="<?php echo base_url().'application/deletesessions';?>"class="btn btn-default">Összes törlése</a><?php

		if (isset($current_id)) {?>

			<a href="<?php echo base_url().'application/exportsession/'.$current_id;?>"class="btn btn-default">Export</a><?php
		
		}?>
		
		<a href="<?php echo base_url().'view/session/import/';?>"class="btn btn-default">Import</a>
	</div>
</div><br />
<div class="row"><?php

if (isset($current_session)) {?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Idő</th>
				<th>Típus</th>
				<th>Név</th>
				<th>Szint</th>
				<th>Eredmény</th>
			</tr>
		</thead>
		<tbody><?php

		if (isset($current_session)) {
			foreach ($current_session as $row) {?>

			<tr>
				<td><?php echo $row['time'];?></td>
				<td><?php echo $row['type'];?></td>
				<td><?php echo $row['name'];?></td>
				<td><?php echo $row['level'];?></td>
				<td><?php echo $row['result'];?></td>
			</tr><?php

			}
		}?>



		</tbody>
	</table><?php
}
?>
</div>