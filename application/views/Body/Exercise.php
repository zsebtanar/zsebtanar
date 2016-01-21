<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-sm-1"><?php

			if ($youtube) {?>

				<a href="http://www.youtube.com/watch?v=<?php echo $youtube; ?>">
					<img src="<?php echo base_url();?>assets/images/logo_small.png" alt="logo" width="20">
				</a>

				<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
							<!-- content dynamically inserted -->
							</div>
						</div>
					</div>
				</div><?php

			}

			if ($hint) {?>

				<a href="#" data-toggle="modal" data-target="#hint">
					<img src="<?php echo base_url();?>assets/images/light_bulb.png" alt="hint" width="40">
				</a>

				<div id="hint" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body text-center">
								<img src="<?php echo base_url().'resources/download/'.$hint; ?>" class="img-responsive" alt="hint" width="100%">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Bezár</button>
							</div>
						</div>
					</div>
				</div><?php

			}?>

			</div>
			<div class="col-sm-10"><?php

			echo $question;?>

			</div>
			<div class="col-sm-1"></div>
		</div>
		<div class="row exercise_input">

			<div class="col-sm-12">
				<form id="exercise" autocomplete="off"><?php

				if ($type == 'quiz') {

					$this->load->view('Input/Quiz', array('options' => $options, 'length' => $length));

				} elseif ($type == 'int' || $type == 'text') {

					$this->load->view('Input/Int');

				} elseif ($type == 'multi') {

					$this->load->view('Input/Multi', array('options' => $options));

				} elseif ($type == 'division') {

					$this->load->view('Input/Division');

				}?>

				<input type="hidden" name="hash" value="<?php echo $hash;?>">
				<br />
				<div class="text-center"><?php

					if ($this->session->userdata('Logged_in')) {

						echo json_encode($correct).'<br />';

					}?>

					<div id="button">
						<button class="btn btn-primary" onclick="checkSolution()">
							Mehet
						</button>
					</div><br />

					<div>
						<a class="btn btn-default" href="<?php echo base_url().'view/subtopic/'.$subtopicID;?>">
							Vissza
						</a>
					</div><br />

					<p id="message"></p>
					<p id="explanation">
						<div class="alert alert-warning text-left"><?php

						if ($this->session->userdata('Logged_in')) {

							print_r($explanation);

						}?>

						</div>
					</p>
					
				</div>
				</form>
			</div>
		</div>
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
						$('.progress-bar').css('width', data['progress']+'%').attr('aria-valuenow', data['progress']);
						if (data['id_next'] == null) {
							document.getElementById("button").innerHTML = "<a class=\"btn btn-primary\" href=\"<?php echo base_url().'view/subtopic/';?>" + data['subtopicID'] + '/' + data['questID'] + "\">Kész! :)</button>";
						} else {
							document.getElementById("button").innerHTML = "<a class=\"btn btn-primary\" href=\"<?php echo base_url().'view/exercise/';?>" + data['id_next'] + "\">Tovább</button>";
						}
						break;
					case 'WRONG':
						if (data['explanation'] != null) {
							document.getElementById("explanation").innerHTML = '<div class="alert alert-warning text-left">' + data['explanation'] + '</div>';
							MathJax.Hub.Queue(["Typeset",MathJax.Hub,"explanation"]);
						}
						document.getElementById("message").innerHTML = '<div class="alert alert-danger"><strong><span class=\"glyphicon glyphicon-remove\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>';
						document.getElementById("button").innerHTML = "<a class=\"btn btn-primary\" href=\"<?php echo base_url();?>view/exercise/<?php echo $id;?>\">Újra</button>";
						if (data['submessages'].length > 0) {
							for (var i = data['submessages'].length - 1; i >= 0; i--) {
								var submessage = data['submessages'][i];
								if (submessage == 'CORRECT') {
									$('#input'+i).before('<span class=\"glyphicon glyphicon-ok green\"></span>&nbsp;');
								} else {
									$('#input'+i).before('<span class=\"glyphicon glyphicon-remove red\"></span>&nbsp;');
								}
							}
						}
						MathJax.Hub.Queue(["Typeset",MathJax.Hub,"message"]);
						break;
				}

				// Change icons
				for (var i = data['level_max'] - 1; i >= 0; i--) {
					var src = document.getElementById("star"+i).src;
					if (i < data['level_user']) {
						var src = src.replace("star0", "star1");
					} else {
						var src = src.replace("star1", "star0");
					}
					document.getElementById("star"+i).src = src;
				}
			}
		});
	}
</script>