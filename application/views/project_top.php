<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>_small.jpg" class="icon" alt="" style="float: left; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #333;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="float: left; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #333;" />
<?php } ?>

<h2 style="margin-left: 6px; float: left; height: 50px; width: 775px;">
	<div style="float: left; height: 30px;"><?php echo $user['username']; ?>'s Projects</div>
	<?php if (!empty($browseby)) { ?>

	<div id="filter" style="margin: none; clear: none; float: right; text-shadow: none; font-size: 10px; letter-spacing: 0px;">
	<ul class="block_links" style="float: left;">
<?php if ($uid != 1 && isset($tracking)) { ?>
		<?php if ($tracking == TRUE) { ?>
		<li><a style="-webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>feedback/untrack/<?php echo $user['id']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/newspaper_link.png" class="icon" alt="Updates" /> Untrack activity</a></li>
		<?php } elseif ($tracking == FALSE && $uid != $this->uid && $user['enable_tracking'] == 1) { ?>
		<li><a style="-webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>feedback/track/<?php echo $user['id']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/newspaper_link.png" class="icon" alt="Updates" /> Track activity</a></li>
		<?php } ?>
<?php } ?>


		<li style="margin: 0px;">
<a style="-webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>profiles/view/<?php echo $user['username']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/photos.png" class="icon" alt="Updates" /> View Latest WIPs</a>
<a style="background-image: linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -o-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -moz-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -webkit-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -ms-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0, rgb(51,51,51)), color-stop(1, rgb(102,102,102))); -webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>profiles/projects/<?php echo $user['id']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/report_picture.png" class="icon" alt="Projects" /> Show by Project</a>
		</li>
	</ul>
	</div>

<div style="width: 100%; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; clear: both; line-height: 25px; font-size: 12px; letter-spacing: 0px; color: #888; background-color: #333; padding: 8px; padding-top: 2px; padding-bottom: 0px; margin-bottom: 0px; position: relative; top: -5px;">
<div style="float: left; text-shadow: 0px 1px 0px #000; color: #CCC;">
<strong><?php echo $user['username']; ?></strong> has made <strong><?php echo $update_count; ?></strong> updates across <strong><?php echo count($pid_array); ?></strong> projects.
</div>
	<div style="clear:both;"></div>
</div>

	<?php } else { ?>
	<div style="float: right; margin-top: -5px;">
	<img src="<?php echo url::base(); ?>images/icons/photos.png" alt="" />
		<ul style="margin-left: 0px; display: inline;">
			<li style="width: 110px; display: inline;">
				<input style="width: 110px; letter-spacing: 0px;" type="button" onclick="parent.location='<?php echo url::base(); ?>profiles/projects/<?php echo $uid; ?>/'" value="View projects" />
			</li>
		</ul>
	</div>
	<?php } ?>
</h2>

<script type="text/javascript">
$(document).ready(function() {
	$("#content_top").css({'min-height': '70px'});
	$("#content_top").animate({height: '70px'});
});
</script>
