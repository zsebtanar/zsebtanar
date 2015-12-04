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
			</tr>
		</thead>
		<tbody><?php

		if (isset($sessions)) {
			foreach ($sessions as $session) {?>

			<tr>
				<td><?php echo $session['id'];?></td>
			</tr><?php

			}
		}?>

		</tbody>
	</table>
</div>