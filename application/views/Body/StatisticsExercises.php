<div class="container">
	<br />
	<a class="btn btn-default" href="<?php echo base_url();?>/view/statistics">
		<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Vissza
	</a>
	<h2>Feladatok</h2>
	<table class="table">
		<thead>
			<tr>
				<th class="text-center">ID</th>
				<th class="text-left">Címke</th>
				<th class="text-center">Idő</th>
				<th class="text-center">Tevékenység</th>
				<th class="text-center">Forrás</th>
			</tr>
		</thead>
		<tbody>
			<?php

            if (count($exercises) > 0) {
                foreach ($exercises as $exercise) {?>
				<tr class="active">
					<td class="text-center">
						<?php echo $exercise['id'];?>
					</td>
					<td class="text-left">
						<a href="<?php echo $exercise['link']['link'];?>" target="_blank">
							<?php echo $exercise['link']['name'];?>
						</a>
					</td>
					<td class="text-center">
						<?php echo $exercise['time'];?>
					</td>
					<td class="text-center">
						<?php echo count($exercise['actions']);?>
					</td>
					<td class="text-center">
						<?php echo $exercise['source'];?>
					</td>
				</tr>
				<?php

                    if (count($exercise['actions']) > 0) {?>
					<tr>
						<td></td>
						<td colspan="4">
							<table class="table">
								<thead>
									<tr>
										<th class="small text-center">ID</th>
										<th class="small text-center">Idő</th>
										<th class="small text-center">Szint</th>
										<th class="small text-center">Segítség</th>
										<th class="small text-center">Eredmény</th>
									</tr>
								</thead>
								<tbody>
									<?php

                                foreach ($exercise['actions'] as $action) {?>
										<tr>
											<td class="small text-center">
												<?php echo $action['id'];?>
											</td>
											<td class="small text-center">
												<?php echo preg_replace('/\d{4}-\d{2}-\d{2}\s/', '', $action['time']);?>
												<?php // echo preg_replace('/\s\d{2}:\d{2}:\d{2}/', '', $action['time']);?>
											</td>
											<td class="small text-center">
												<?php echo $action['level'];?>
											</td>
											<td class="small text-center">
												<?php echo $action['usedHints'].'/'.$action['allHints'];?>
											</td>
											<td class="small text-center">
												<?php echo $action['result'];?>
											</td>
										</tr>
										<?php
                                    
		                         }?>
								</tbody>
							</table>
						</td>
					</tr>
					<?php

                    }
                }
            }?>
		</tbody>
	</table>
</div>
