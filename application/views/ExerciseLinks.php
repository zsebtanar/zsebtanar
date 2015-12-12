<ul>
	<li>
		<a href="<?php echo base_url().'view/exercise/'.$links['id'];?>" target="_blank">
			<?php echo $links['name'];?>
		</a><?php

		if (count($links['links']) > 0) {

			foreach ($links['links'] as $link) {
				$this->load->view('ExerciseLinks', array('links' => $link));
			}
		}?>

	</li>
</ul>