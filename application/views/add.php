<!DOCTYPE html>
<html>
<head>
	<title>A Memorial for a Game of Giant Imaginations</title>
	<?php $this->load->view('includes'); ?>
</head>
<body>
	<p class="top-link">
		<a href="#" id="refresh">Refresh</a>
		<a href="<?=site_url()?>">Back to memorials →</a>
	</p>
	<div id="remember">
		<form id="add-tweet" action="<?=site_url('add/save')?>" method="POST">
			I'll always remember Glitch for

			<textarea name="post" maxlength="<?=$max_length?>"></textarea>

			<p id="character-count"></p>
			<input type="hidden" name="type" value="tweet" />

			<button>Immortalize Me!</button> 
			<a href="#" id="prose-switch">I need more than 140 characters!</a>
		</form>

		<form id="add-prose" action="<?=site_url('add/save')?>" method="POST">
			<textarea name="post">I'll always remember Glitch for </textarea>
			<input type="hidden" name="type" value="prose" />
			<button>Immortalize Me!</button> 
			<a id="format-guide" href="http://daringfireball.net/projects/markdown/syntax">
			You can use Markdown for formatting.</a>
		</form>
	</div>

	<div id="memories" class="faded"></div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="<?=site_url('assets/js/jquery.masonry.min.js')?>"></script>
	<script src="<?=site_url('assets/js/master.js')?>"></script>
</body>
</html>