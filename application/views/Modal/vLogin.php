<div id="login" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Bejelentkezés</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<form role="form">
							<div class="form-group">
								<label for="pwd">Jelszó:</label>
								<input id="password" type="password" class="form-control" id="pwd">
							</div>
							<p class="text-center" id="message"></p>
							<div class="text-right">
								<button type="submit" class="btn btn-default" onclick=login()>Mehet</button>
							</div>
						</form>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Typeahead JS -->
<script>
    function login() {
        $.ajax({
            type: "GET",
            url: "<?php echo base_url();?>application/login",
            data: {
                keyword: $("#password").val()
            },
            dataType: "json",
            success: function(status) {
                if (status == 'PASSWORD_OK') {
                    location.reload();
                } else if (status == 'INCORRECT_PASSWORD') {
                    $('#message').val('Érvénytelen jelszó!');
                } else if (status == 'PASSWORD_MISSING') {
                	$('#message').val('Hiányzik a jelszó!');
    			}
    		}
        });
    }
</script>