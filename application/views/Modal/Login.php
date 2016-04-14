<div id="login" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Admin</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="pwd">Jelszó:</label>
							<input name="password" type="password" class="form-control" id="pwd">
						</div>
						<div class="text-right">
							<a class="btn btn-default" href="#" onclick="login(event)">Mehet</a>
						</div>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function login(event){
		var pwd = document.getElementById('pwd').value;
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>action/login/"+pwd
		});
		location.reload();
	}
</script>