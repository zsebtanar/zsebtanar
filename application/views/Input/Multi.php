<?php
foreach ($options as $key => $value) {?>
	
	<input id="input<?php echo $key;?>" type="checkbox" name="answer" value="<?php echo $key; ?>">&nbsp;<?php echo $value; ?><br />
	<?php
}?>