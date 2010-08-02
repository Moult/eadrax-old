<script language="text/javascript">
function toggle(obj) {
	var el = document.getElementById(obj);
	el.style.display = (el.style.display != 'none' ? 'none' : '' );
}
</script>

<div style="background-image: url('<?php echo url::base(); ?>images/content_top_bg.png'); background-repeat: repeat-x; padding-left: 10px; padding-right: 10px; min-height: 450px;">
	<h2 style="font-size: 25px; color: #FF7600;">
		<img src="<?php echo url::base(); ?>images/icons/clock_48.png" width="48" height="48" class="icon" alt="" />
		Compare diffs
	</h2>

	<p style="margin-bottom: 10px; margin-top: 10px; width: 800px;">
		Revisions are revised versions of pastebin content. Diffs show the changes that have occured between revisions. Click on a revision to compare against it, or the clock icon to jump to the update.
	</p>

<?php foreach ($update_revisions as $update_revision) { if ($update_revision['id'] != $uid) { ?>
	<p style="margin-bottom: 10px; width: 800px;">
<span onclick="javascript:toggle('diff<?php echo $update_revision['id']; ?>');">
<span style="font-weight: bold; font-size: 16px;"><a href="<?php echo url::base(); ?>updates/view/<?php echo $update_revision['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/clock.png" alt="revision" /></a> <?php echo $update_revision['summary']; ?></span><br />
<?php $user_model = new User_Model; $uid_info = $user_model->user_information($update_revision['uid']); ?>
by <a href="<?php echo url::base(); ?>profiles/view/<?php echo $uid_info['username']; ?>/"><?php echo $uid_info['username']; ?></a> on <span style="color: #666;"><?php echo date('jS F Y', strtotime($update_revision['logtime'])); ?></span>
</span>
	</p>

<pre id="diff<?php echo $update_revision['id']; ?>" style="display: none; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; background-color: #FFF; border: 1px solid #CCC; padding: 8px; margin-bottom: 10px;">
<?php $i = 0; foreach ($diffs[$update_revision['id']] as $diff) { $i++; if ($i > 2) { ?>
<?php if (substr($diff, 0, 1) == '+') { ?><span style="color: #00b000;"><?php echo $diff; ?></span><br /><?php } elseif (substr($diff, 0, 1) == '-') {?><span style="color: #991111;"><?php echo $diff; ?></span><br /><?php } elseif (substr($diff, 0, 1) == '@') { ?><span style="color: #440088; font-weight: bold;"><?php echo $diff; ?></span><br /><?php } else { ?><?php echo $diff; ?><br /><?php } ?>
<?php } } ?>
</pre>

<?php } } ?>
</div>
