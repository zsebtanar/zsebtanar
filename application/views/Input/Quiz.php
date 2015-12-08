<?php
if (count($options) > 3) {?>

	<select name="answer" class="form-control"><?php

	foreach ($options as $key => $value) {?>
		
		<option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php

	}?>

	</select><?php

} else {

	foreach ($options as $key => $value) {?>
		
		<div class="radio">
			<label>
				<input type="radio" name="answer" value="<?php echo $key; ?>"><?php echo $value; ?>
			</label>
		</div><?php

	}
}?>