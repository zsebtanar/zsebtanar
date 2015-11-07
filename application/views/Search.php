<div id="search" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Keresés</h4>
			</div>
			<div class="modal-body">
				<form action="index.php" method="post">
					<p id="result-container"></p>
					<div class="typeahead-container">
						<div class="typeahead-field">
							<span class="typeahead-query">
								<input id="q" name="q" type="search" autofocus autocomplete="off">
							</span>
							<span class="typeahead-button">
								<button type="submit">
									<span class="typeahead-search-icon"></span>
								</button>
							</span>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Bezár</button>
			</div>
		</div>
	</div>
</div>