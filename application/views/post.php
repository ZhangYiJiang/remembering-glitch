<!DOCTYPE html>
<html>
<head>
	<title>A Memorial for a Game of Giant Imaginations</title>
	<?php $this->load->view('includes'); ?>
</head>
<body>
	<?php $this->load->view('top_links') ?>
	
	<div id="remember">
		<?php $this->load->view('post_content'); ?>

		<?php if (count($snaps)): ?>
		<ul class="snaps">
			<?php foreach($snaps as $snap): ?>
			<li><a href="<?=site_url("/assets/snap/$snap[snap_id].jpeg")?>">
				<img src="<?=site_url("/assets/snap/$snap[snap_id].jpeg")?>" 
				alt="" title="<?=$snap[caption]?>" /></a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php elseif ($player_id == $this->session->userdata('player_id')): ?>
		
		<div id="add-snap">
			<h3><span>Add a snap to this memory</span></h3>

			<label for="snap_url">Long URL of the snap page</label>
			<input name="snap" type="text" id="snap_url" />
			<button>Submit</button>

			<p class="form-example">eg. http://www.glitch.com/snaps/PHVFSV9R04A2DO9/208772-787b116ccd/</p>

			<input name="post" id="post_id" value="<?=$id?>" type="hidden" />
		</div>
		<?php endif; ?>
	</div>
	
	<div id="memories" class="faded"></div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="<?=site_url('assets/js/jquery.masonry.min.js')?>"></script>
	<script src="<?=site_url('assets/js/master.js')?>"></script>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</body>
</html>