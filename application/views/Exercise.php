<!-- Check Soluction JS -->
<script>
    function checkAnswer() {
    	alert('afdasfasdf');
        var queryString = $('#exercise').formSerialize();
        

    }
</script>

<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="row">
			<div class="col-md-1"><?php

			if ($youtube) {?>

				<a href="http://www.youtube.com/watch?v=<?php echo $youtube; ?>">
					<img src="<?php echo base_url();?>/assets/images/logo_small.png" alt="logo" width="20">
				</a><?php

			}?>

			</div>
			<div class="col-md-10"><?php

			echo $question;?>

			</div>
			<div class="col-md-1"></div>
		</div>
		<div class="row exercise_input">
			<div class="col-sm-12 text-center">
				<form autocomplete="off"><?php

				if ($type == 'int') {

					if (is_array($options)) {

						if (count($options) > 3) {?>

							<select name="answer" class="form-control" id="sel<?php echo $ex_no; ?>"><?php

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
						}

					} elseif ($options == '') {?>

						<table align="center" class="answer_fraction">
							<tbody>
								<tr>
									<td align="center">
										<input type="text" align="center" class="form-control smallInput" data-autosize-input='{ "space": 20 }' name="answer">
									</td>
								</tr>
							</tbody>
						</table>
						<?php

					}

				}?>

				<input type="hidden" name="type" value="<?php echo $type;?>">
				<input type="hidden" name="correct" value="<?php echo $correct;?>">
				<input type="hidden" name="solution" value="<?php echo $solution;?>">
				
			
				<p id="error"></p>
				<button class="btn btn-primary" type="submit">
					Mehet
				</button>
			
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-4"></div>
</div>

<!-- Check Soluction JS -->
<script>

	$( "form" ).submit(function( event ) {
		var queryString = $( this ).serializeArray();
		event.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo base_url();?>application/checkanswer",
	        data: {
	            answer: JSON.stringify(queryString)
	        },
	        dataType: "json",
        	success: function(data) {
	        	alert(data);
	        }
	    });
    });
</script>