<?php
foreach ($options as $key => $value) { ?>

    <div>
        <label class="control-label">
            <input id="input<?php echo $key; ?>" type="checkbox" name="answer" value="<?php echo $key; ?>">
            &nbsp;<?php echo $value; ?>
        </label>
    </div>
    <?php
} ?>