<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form action="<?php echo base_url();?>application/search" method="post">
			<div class="typeahead-container">
				<div class="typeahead-field">
					<span class="typeahead-query">
						<input name="keyword" type="search" autofocus autocomplete="off">
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
	<div class="col-md-4"></div>
</div>