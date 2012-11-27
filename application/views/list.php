<!DOCTYPE html>
<html>
<head>
	<title>A Memorial for a Game of Giant Imaginations</title>
	<?php $this->load->view('includes'); ?>
</head>
<body>
	<?php $this->load->view('top_links') ?>
	
	<div id="remember" class="list">
		<?php foreach ($post as $row): ?>
		<div class="item">
			<a href="<?=site_url($row['id'])?>" class="item-number">
				<span>No. </span><?=$row['id']?></a>
			<?php $this->load->view('post_content', $row); ?>
		</div>
		<?php endforeach; ?>


		<?=$this->pagination->create_links()?>
	</div>
	
	<div id="memories" class="faded"></div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="<?=site_url('assets/js/jquery.masonry.min.js')?>"></script>
	<script src="<?=site_url('assets/js/master.js')?>"></script>
</body>
</html>