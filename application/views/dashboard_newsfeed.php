<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/newspaper_48.png" class="icon" alt="" />
		Your Newsreel
	</h2>

	<p>
		Your newsfeed displays updates on your own submissions, as well as those of users whom you have tracked or individual projects which you have subscribed to.
	</p>

	<?php if (empty($newsfeed)) { ?>
	<p>
		Uh oh! It looks as though you currently are <strong>not</strong> tracking anyone or subscribed to any project. Why don't you browse the <a href="<?php echo url::base(); ?>projects/view/">latest updates</a> or try your luck to see if a <a href="<?php echo url::base(); ?>updates/random/">random update</a> interests you?
	</p>
	<?php } ?>

	<?php
	foreach ($newsfeed as $news) {
	?>
		<div style="clear: both; margin-bottom: 10px; overflow: hidden; border-bottom: 1px dotted #AAA; position: relative;">

			<div style="text-align: center; position: absolute; right: 0px; bottom: 0px; font-size: 10px; color: #999;">
				<?php echo $news['logtime']; ?>
			</div>

			<div style="float: left;">
				<?php if (!empty($news['avatar'])) { ?>
				<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $news['avatar']; ?>_small.jpg" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
				<?php } else { ?>
				<img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
				<?php } ?>
			</div>
			<div style="float: left; margin-left: 5px; width: 480px;">
				<p style="color: #555;">
				<strong><a href="<?php echo url::base(); ?>profiles/view/<?php echo $news['uid']; ?>/" style="text-decoration: none;"><?php echo $news['user']; ?></a></strong> <?php echo $news['text']; ?>
				</p>
	<?php
		if (!strpos($news['picture'], 'images/icons') || strpos($news['picture'], 'images/noprojecticon.png')) { $style = 'border: 1px solid #999; padding: 1px;'; } else { $style = ''; }
		echo '<p style="float: left;"><a href="'. $news['picture_url'] .'"><img style="vertical-align: middle; '. $style .'" src="'. $news['picture'] .'" alt="update icon" /></a></p>';
	?>

	<?php if (!empty($news['comment_text'])) { ?>
	<span style="float: left; font-size: 14px; font-style: italic; margin-left: 5px; width: 300px;">
	<?php echo $news['comment_text']; ?>
	</span>
	<?php } ?>

	<?php if (!empty($news['update_text'])) { ?>
	<p style="float: left; margin-left: 5px; width: 300px;">
		<span style="font-size: 14px;">
			<strong><a href="<?php echo $news['picture_url']; ?>" style="text-decoration: none;"><?php echo $news['update_text']; ?></a></strong>
		</span><br />
		<?php if (!empty($news['extra_text'])) { ?>
		<?php echo $news['extra_text']; ?>
		<?php } ?>
	</p>
	<?php } ?>

			</div>
		</div>
	<? } ?>
</div>

<div class="right">

	<div class="form">
		<h3>
			<img src="<?php echo url::base(); ?>images/icons/group_link.png" alt="" width="16" height="16" class="icon" />
			Trackers (<?php echo $track_total; ?>)
		</h3>
		<div class="elements" style="overflow: hidden;">
			<?php
			$i = 0;
			foreach ($track_list as $value) {
				$i++;
				if ($i != 4) {
					$markup = 'margin-right: 8px;';
				} else {
					$markup = '';
					$i = 0;
				}
			?>
			<div style="float: left; <?php echo $markup; ?>">
			<img src="<?php echo $value[2]; ?>" style="border: 1px solid #999; padding: 1px;" title="<?php echo $value[1]; ?>" alt="<?php echo $value[1]; ?>" />
			</div>
			<?php } ?>
			<?php if ($track_total == 0) { ?>
			Nobody's tracking you at the moment. Aww.
			<?php } ?>
		</div>
	</div>

	<div class="form">
		<h3>
			<img src="<?php echo url::base(); ?>images/icons/newspaper_link.png" alt="" width="16" height="16" class="icon" />
			Subscribers (<?php echo $subscribe_total; ?>)
		</h3>
		<div class="elements" style="overflow: hidden;">
			<?php if ($subscribe_total == 0) { ?>
			Nobody's subscribed to your stuff. :(
			<?php } ?>
			<?php foreach($project_subscribe_list as $pid => $p_array) { ?>
			<h3 style="clear: both; margin-bottom: 10px;">
			<?php echo $p_array[0]; ?> (<?php echo $p_array[1]; ?>)
			</h3>
			<?php
			$i = 0;
			foreach ($p_array[2] as $value) {
				$i++;
				if ($i != 4) {
					$markup = 'margin-right: 8px;';
				} else {
					$markup = '';
					$i = 0;
				}
			?>
			<div style="float: left; margin-bottom: 10px; <?php echo $markup; ?>">
			<img src="<?php echo $value[2]; ?>" style="border: 1px solid #999; padding: 1px;" title="<?php echo $value[1]; ?>" alt="<?php echo $value[1]; ?>" />
			</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
