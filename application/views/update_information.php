<div style="background-image: url('<?php echo url::base(); ?>images/content_top_bg.png'); background-repeat: repeat-x; padding-left: 10px; padding-right: 10px;">
	<h2 style="font-size: 25px; color: #FF7600;">
		<img src="<?php echo url::base(); ?>images/icons/globe_48.png" width="48" height="48" class="icon" alt="" />
		You should share it.
	</h2>

	<p style="margin-bottom: 10px; margin-top: 10px; width: 500px;">
		It doesn't matter if your update looks horrible, <strong>your update is a valued part of the creation process</strong> and deserves every right to be proudly showcased out there for the world to see. At WIPUP, we believe that <strong>this in-between is just as important and breathtakingly awesome as the finished product iself</strong> - it's the hidden spectacular adventure behind the creation. Anything from a brainwave of an idea to a tiny nudge of a pixel - we want you to use WIPUP to suit your workflow.
	</p>

	<p>
		You can start sharing this update with your friends by directing them to this URL:<br /><input type="text" style="width: 300px;" value="<?php echo url::base(); ?>updates/view/<?php echo $uid; ?>/" />
	</p>

	<?php if ($this->logged_in) { ?>
	<p>
		Or link them straight to the profile:<br /><input type="text" style="width: 300px;" value="<?php echo url::base(); ?>profiles/view/<?php echo $username; ?>/" />
	</p>
	<?php } ?>

	<?php
	$twitter = url::base() .'updates/view/'. $uid .'/ '. $summary;
	if (strlen($twitter) > 140) {
		$twitter = substr($twitter, 0, 137) .'...';
	}
	?>

	<p style="margin-bottom: 10px; margin-top: 10px; width: 500px;">
		For those social folk we've got a few shortcuts to share on your social network's stream.
	</p>
	<p>
		<a href="http://twitter.com/home?status=<?php echo $twitter; ?>" target="_blank" rel="nofollow"><img src="<?php echo url::base(); ?>images/icons/twitter.png" alt="Twitter" /></a>
	<a href="http://facebook.com/share.php?u=<?php echo url::base(); ?>updates/view/<?php echo $uid; ?>/&t=<?php echo $summary; ?>" target="_blank" rel="nofollow"><img src="<?php echo url::base(); ?>images/icons/facebook.png" alt="Facebook" /></a>
	<a href="http://digg.com/submit?phase=2&url=<?php echo url::base(); ?>updates/view/<?php echo $uid; ?>/&title=<?php echo $summary; ?>" target="_blank" rel="nofollow"><img src="<?php echo url::base(); ?>images/icons/digg.png" alt="Digg" /></a>
	<a href="http://del.icio.us/post?url=<?php echo url::base(); ?>updates/view/<?php echo $uid; ?>/&title=<?php echo $summary; ?>" target="_blank" rel="nofollow"><img src="<?php echo url::base(); ?>images/icons/delicious.png" alt="Delicious" /></a>
	</p>
</div>
