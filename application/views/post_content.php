<?php if (isset($twitter) && $twitter): ?>
<a href="https://twitter.com/share" data-text="<?=$tweet?>" 
	data-url="<?=site_url($id)?>" data-count="none" 
	class="twitter-share-button" data-lang="en">Tweet</a>
<?php endif; ?>

<?php if ($type == "tweet"): ?>
<div class="tweet-header">I'll always remember Glitch for</div>
<?php endif; ?>

<div class="post <?=$type?>">
	<?=$post?>
</div>

<div class="meta">
	<span class="avatar" style="background-image:url(<?=site_url("/assets/avatar/$player_id.png")?>)">
	</span>
	<p class="post-name"><a href="<?=site_url("player/$player_id")?>">
		<?=$name?></a></p>
	<a href="<?=site_url($id)?>" class="post-time" title="<?=$timestamp?>"><?=$glitch_time?></span>
	<a class="add-another" href="<?=site_url('add')?>">
		<?php if ($this->session->userdata('player_id') == $player_id): ?>
		Add another one ►
		<?php else: ?>
		Add yours ►
		<?php endif; ?>
	</a>
</div>