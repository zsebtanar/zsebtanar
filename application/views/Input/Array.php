<div class="text-center">
	<table align="center">
		<tbody>
			<tr><?php

				foreach ($labels as $label) {?>

				<td align="center">
					<?php echo $label; ?><input type="text" align="center" autofocus="autofocus" class="form-control smallInput" data-autosize-input='{ "space": 20 }' name="answer">
				</td><?php

				}?>
			</tr>
		</tbody>
	</table>
</div>