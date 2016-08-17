<div class="text-center">
	<table align="center">
		<tbody><?php

			if (count($labels) > 1) {

				foreach ($labels as $label) {?>

				<tr>
					<td align="center"><?php echo $label; ?></td>
					<td align="center">(<input type="text" align="center" autofocus="autofocus" class="form-control form-control-inline smallInput" data-autosize-input='{ "space": 20 }' name="answer">;<input type="text" align="center" autofocus="autofocus" class="form-control form-control-inline smallInput" data-autosize-input='{ "space": 20 }' name="answer">)</td>
				</tr><?php

				}
				
			} else {?>

				<tr>
					<td align="center"><?php echo $labels; ?></td>
					<td align="center">(<input type="text" align="center" autofocus="autofocus" class="form-control form-control-inline smallInput" data-autosize-input='{ "space": 20 }' name="answer">;<input type="text" align="center" autofocus="autofocus" class="form-control form-control-inline smallInput" data-autosize-input='{ "space": 20 }' name="answer">)</td>
				</tr><?php

			}?>

		</tbody>
	</table>
</div>