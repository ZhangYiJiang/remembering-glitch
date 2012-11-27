<!DOCTYPE html>
<html>
<head>
	<title>A Memorial for a Game of Giant Imaginations</title>
	<?php $this->load->view('includes'); ?>
</head>
<body>
	<?php $this->load->view('top_links') ?>

	<div id="remember">
		<div id="home">
			<p>I'll always remember 
			<img src="<?=site_url("assets/image/glitch-logo.png")?>" alt="Glitch" /> 
			for...</p>

			<p class="home-meta">
				<?=$player_count?> players have added <?=$post_count?> memories
				<a href="<?=site_url('add')?>">Add yours ►</a>
			</p>
		</div>

		<a href="#" id="hide-remember">❌ hide</a>
	</div>

	<div id="memories"></div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="<?=site_url('assets/js/jquery.masonry.min.js')?>"></script>
	<script src="<?=site_url('assets/js/master.js')?>"></script>
</body>
</html>