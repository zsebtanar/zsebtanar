<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-1"><?php

			if ($youtube) {?>

				<a href="http://www.youtube.com/watch?v=<?php echo $youtube; ?>">
					<img src="<?php echo base_url();?>assets/images/logo_small.png" alt="logo" width="20">
				</a><?php

			}

			if ($download) {?>

				<a href="<?php echo base_url().'resources/download/'.$download; ?>" target="_blank">
					<img src="<?php echo base_url();?>assets/images/light_bulb.png" alt="hint" width="40">
				</a><?php

			}?>

			</div>
			<div class="col-md-11"><?php

			echo $question;?>

			</div>
		</div>
		<div class="row exercise_input">

			<div class="col-sm-12">
				<form id="exercise" autocomplete="off"><?php

				if ($type == 'quiz') {

					$this->load->view('Input/Quiz', array('options' => $options, 'length' => $length));

				} elseif ($type == 'int') {

					$this->load->view('Input/Int');

				} elseif ($type == 'multi') {

					$this->load->view('Input/Multi', array('options' => $options));

				}?>

				<input type="hidden" name="hash" value="<?php echo $hash;?>">
				<br />
				<div class="text-center">
					<p id="message"></p>
					<div id="button"><?php

						if ($this->session->userdata('Logged_in')) {

							echo json_encode($correct).'<br />';

						}?>

						<button class="btn btn-primary" onclick="checkSolution()">
							Mehet
						</button>
					</div><br />
					<?php

				if ($id_prev) {?>

					<a class="btn btn-default" href="<?php echo base_url().'view/exercise/'.$id_prev;?>">
						Könnyebbet kérek!
					</a><?php

				}?>


				</div>
				</form>
			</div>
		</div><?php

		if ($this->session->userdata('Logged_in') && count($links['links']) > 0) {

				$this->load->view('ExerciseLinks', array('links' => $links));
			
		}?>

	</div>
	<div class="col-md-3"></div>
</div>

<script>

	// Check solution
	function checkSolution() {
		var queryString = $("form").serializeArray();
		event.preventDefault();
		$.ajax({
			type: "GET",
			url: "<?php echo base_url();?>application/checkanswer",
			data: {
				answer: JSON.stringify(queryString)
			},
			dataType: "json",
			success: function(data) {
				document.getElementById("message").innerHTML = '';
				
				// Exercise not finished
				if (data['status'] == 'NOT_DONE') {
					document.getElementById("message").innerHTML = data['message'];
					return;
				}

				// Disable buttons
				var radios = document.forms["exercise"]["answer"];

				if (radios.length > 0) {
					for (var i=0, iLen=radios.length; i<iLen; i++) {
						radios[i].disabled = true;
					}
				} else {
					radios.disabled = true;
				}
				
				// Exercise finished
				switch (data['status']) {
					case 'CORRECT':
						document.getElementById("message").innerHTML = '<div class="alert alert-success"><strong><span class=\"glyphicon glyphicon-ok\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>';
						if (data['id_next'] == null) {
							document.getElementById("button").innerHTML = "<a class=\"btn btn-primary\" href=\"<?php echo base_url().'view/subtopic/';?>" + data['subtopicID'] + "\">Kész! :)</button>";
						} else {
							document.getElementById("button").innerHTML = "<a class=\"btn btn-primary\" href=\"<?php echo base_url().'view/exercise/';?>" + data['id_next'] + "\">Tovább</button>";
						}
						break;
					case 'WRONG':
						document.getElementById("message").innerHTML = '<div class="alert alert-danger"><strong><span class=\"glyphicon glyphicon-remove\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>';
						MathJax.Hub.Queue(["Typeset",MathJax.Hub,"message"]);
						if (data['submessages'].length > 0) {
							for (var i = data['submessages'].length - 1; i >= 0; i--) {
								var submessage = data['submessages'][i];
								if (submessage == 'CORRECT') {
									$('#input'+i).before('<span class=\"glyphicon glyphicon-ok green\"></span>&nbsp;');
								} else {
									$('#input'+i).before('<span class=\"glyphicon glyphicon-remove red\"></span>&nbsp;');
								}
							};
						}
						document.getElementById("button").innerHTML = "<a class=\"btn btn-primary\" href=\"<?php echo base_url();?>view/exercise/<?php echo $id;?>\">Újra</button>";
						break;
				}

				// Change icons
				$.each(data['levels'], function(index, value) {
					console.log(value);
					var src = document.getElementById("star"+index).src;
					if (value == 0) {
						var src = src.replace("1", "0");
					} else {
						var src = src.replace("0", "1");
					}
					document.getElementById("star"+index).src = src;
					console.log(src);

				});
			}
		});
	}
</script>