<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-sm-1"><?php

			if ($youtube) {?>

				<a href="http://www.youtube.com/watch?v=<?php echo $youtube; ?>">
					<img src="<?php echo base_url();?>assets/images/play.png" alt="logo" width="30">
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
		<div class="row">
			<div class="col-sm-12 exercise_input">
				<form id="exercise_form" autocomplete="off"><?php

					if ($type == 'quiz') {

						$this->load->view('Input/Quiz',
							array('options' => $options,
									'width' => $width,
									'align' => $align));

					} elseif ($type == 'int' || $type == 'text') {

						$this->load->view('Input/Default');

					} elseif ($type == 'multi') {

						$this->load->view('Input/Multi', array('options' => $options));

					} elseif ($type == 'division') {

						$this->load->view('Input/Division');

					} elseif ($type == 'fraction') {

						$this->load->view('Input/Fraction');

					}?>

					<input type="hidden" name="hash" value="<?php echo $hash;?>">
					<div class="text-center"><?php

						if ($this->Session->CheckLogin()) {

							echo $type.'<br />';
							echo json_encode($correct).'<br />';

						}?>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<a class="btn btn-default pull-left" href="<?php echo base_url().'view/subtopic/'.$subtopicID.'/'.$id;?>">
					<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Vissza
				</a>
				<button id="next_button" class="btn btn-primary pull-right" onclick="checkSolution(event)">
					Tovább&nbsp;<span class="glyphicon glyphicon-chevron-right">
				</button>
				<br/>
				<br/><?php

				if ($hints_all > 0) {?>

				<div>
					<button id="hint_button" class="btn btn-danger pull-right" onclick="addexplanation(event)">
						Segítséget kérek!
					</button><br /><br />
					<p id="hints_left" class="small pull-right">
						(<?php echo $hints_all-$hints_used;?> segítség maradt.)
					</p>
				</div><?php

				}

				?>


				<br/>
				<br/>

				<div id="message"></div>
			</div>
		</div>
		<div class="row">
			<div id="explanation" class="col-sm-12"></div>
		</div>
		<div class="row"><?php

			if ($this->Session->CheckLogin() && isset($explanation)) {?>

			<div class="col-sm-12"><?php

				foreach ($explanation as $hint) {
					print_r('<p>'.$hint.'</p>');
				}?>

			</div><?php

			}?>
		</div>
	</div>
	<div class="col-md-3"></div>
</div>

<script>

	document.onkeypress = keyPress;

	function keyPress(e){
		var x = e || window.event;
		var key = (x.keyCode || x.which);
		if(key == 13 || key == 3){
			if ($("#next_button").attr('href')) {
				window.location.href = $("#next_button").attr('href');
			} else {
				checkSolution(event);
			}
		}
	}

	function addexplanation(event){
		var hash = $('[name="hash"]').attr('value');
		event.preventDefault();
		$.ajax({
			type: "GET",
			url: "<?php echo base_url();?>application/gethint/"+hash,
			success: function(data) {
				var explanation = jQuery.parseJSON(data)['explanation'];
				var hints_all = jQuery.parseJSON(data)['hints_all'];
				var hints_used = jQuery.parseJSON(data)['hints_used'];
				var hints_left = hints_all - hints_used;
				$("#explanation").append('<p>'+explanation+'</p>');
				MathJax.Hub.Queue(["Typeset",MathJax.Hub,"explanation"]);
				$("#hints_left").html("("+hints_left+" segítség maradt.)");
				if (hints_left == 0) {
					$("#hint_button").attr('class',"btn btn-danger pull-right disabled");
				}
			}
		});
	}

	// Check solution
	function checkSolution(event) {
		var queryString = $("#exercise_form").serializeArray();
		event.preventDefault();
		$.ajax({
			type: "GET",
			url: "<?php echo base_url();?>application/checkanswer",
			data: {
				answer: JSON.stringify(queryString)
			},
			dataType: "json",
			success: function(data) {
				
				// Exercise not finished
				if (data['status'] == 'NOT_DONE') {
					$("#message").html(data['message']);
					MathJax.Hub.Queue(["Typeset",MathJax.Hub,"message"]);
					return;
				}

				// Disable buttons
				var radios = document.forms["exercise_form"]["answer"];

				if (radios.length > 0) {
					for (var i=0, iLen=radios.length; i<iLen; i++) {
						radios[i].disabled = true;
					}
				} else {
					radios.disabled = true;
				}

				// Disable hint button
				$("#hint_button").attr('class',"btn btn-danger pull-right disabled");

				// Exercise finished
				switch (data['status']) {
					case 'CORRECT':
						$("#message").replaceWith('<div class="alert alert-success"><strong><span class=\"glyphicon glyphicon-ok\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>');
						$('#progress_bar').css('width', data['progress']['value']+'%').attr('aria-valuenow', data['progress']['value']);
						progress_bar_class = $('#progress_bar').attr('class').replace(/(progress-bar-)\w*/, '$1'+ data['progress']['style']);
						$('#progress_bar').attr('class', progress_bar_class);
						if (data['id_next'] == null) {
							$("#next_button").replaceWith("<a id=\"next_button\" class=\"btn btn-primary pull-right\" href=\"<?php echo base_url().'view/subtopic/';?>" + data['subtopicID'] + '/' + data['questID'] + "\">Kész! :)</button>");
						} else {
							$("#next_button").replaceWith("<a id=\"next_button\" class=\"btn btn-primary pull-right\" href=\"<?php echo base_url().'view/exercise/';?>" + data['id_next'] + "\">Tovább&nbsp;<span class=\"glyphicon glyphicon-chevron-right\"></span></button>");
						}
						break;
					case 'WRONG':
						if (data['explanation'] != null) {
							$("#exercise_explanation").replaceWith('<div class="alert alert-warning text-left">' + data['explanation'] + '</div>');
							MathJax.Hub.Queue(["Typeset",MathJax.Hub,"hint"]);
						}
						$("#message").replaceWith('<div class="alert alert-danger"><strong><span class=\"glyphicon glyphicon-remove\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>');
						$("#next_button").replaceWith("<a id=\"next_button\" class=\"btn btn-primary pull-right\" href=\"<?php echo base_url();?>view/exercise/<?php echo $id;?>\">Újra&nbsp;<span class=\"glyphicon glyphicon-refresh\"></span></button>");
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
			}
		});
	}
</script>