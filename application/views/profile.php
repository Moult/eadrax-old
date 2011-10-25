<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>.jpg" class="icon" alt="" style="float: left; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #333;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/noavatar.png" class="icon" alt="" style="float: left; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #333;" />
<?php } ?>

<h2 style="margin-left: 6px; float: left; height: 82px; width: 746px;">
	<div style="float: left; margin-bottom: -13px; height: 30px;"><?php echo $user['username']; ?></div>
	<?php if (!empty($browseby)) { ?>
	<div style="float: right; margin-top: -5px;">
		<img src="<?php echo url::base(); ?>images/icons/report_picture.png" alt="" />
		<ul style="margin-left: 0px; display: inline;">
			<li style="width: 120px; display: inline;">
				<input style="width: 120px; letter-spacing: 0px;" type="button" onclick="parent.location='<?php echo url::base(); ?>profiles/view/<?php echo $user['username']; ?>/'" value="Back to updates" />
			</li>
		</ul>
	</div>
	<?php } else { ?>
	<div id="filter" style="margin: none; clear: none; float: right; text-shadow: none; font-size: 10px; letter-spacing: 0px;">
	<ul class="block_links" style="margin-bottom: 10px;">
<?php if ($uid != 1) { ?>
		<?php if ($tracking == TRUE) { ?>
		<li><a style="-webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>feedback/untrack/<?php echo $user['id']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/newspaper_link.png" class="icon" alt="Updates" /> Untrack activity</a></li>
		<?php } elseif ($tracking == FALSE && $uid != $this->uid && $user['enable_tracking'] == 1) { ?>
		<li><a style="-webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>feedback/track/<?php echo $user['id']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/newspaper_link.png" class="icon" alt="Updates" /> Track activity</a></li>
		<?php } ?>
<?php } ?>
		<li style="margin: 0px;">
<a style="background-image: linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -o-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -moz-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -webkit-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -ms-linear-gradient(bottom, rgb(51,51,51) 0%, rgb(102,102,102) 100%); background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0, rgb(51,51,51)), color-stop(1, rgb(102,102,102))); -webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>profiles/view/<?php echo $user['username']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/photos.png" class="icon" alt="Updates" /> View Latest WIPs</a>
<a style="-webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; border-top-left-radius: 5px; border-top-right-radius: 5px; position: relative; top: 8px;" href="<?php echo url::base(); ?>profiles/projects/<?php echo $user['id']; ?>" class="block"><img src="<?php echo url::base(); ?>images/icons/report_picture.png" class="icon" alt="Projects" /> Show by Project</a>
</li>
	</ul>


	</div>
	<?php } ?>
<div style="width: 100%; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; clear: both; line-height: 25px; font-size: 12px; letter-spacing: 0px; color: #888; background-color: #333; padding: 8px; padding-top: 2px; padding-bottom: 0px; margin-bottom: 0px;">
<div style="float: left; text-shadow: 0px 1px 0px #000; color: #CCC;">
<?php if (!empty($age)) { ?>
<?php if ($age == 'no') { ?>
Some <?php } else { ?>
<strong><?php echo $age; ?></strong> year old
<?php } ?>
<?php if ($user['gender'] != 'Confused') { ?>
<strong><?php echo strtolower($user['gender']); ?></strong> 
<?php } else { ?>
gender-confused person
<?php } ?>
<?php if (!empty($user['location'])) { ?>
living in <strong><?php echo $user['location']; ?></strong>
<?php } ?>
<?php } else { ?>
A sociopath who hasn't yet updated his profile information
<?php } ?>
<?php if ($this->uid == $user['id']) { ?> <a href="<?php echo url::base(); ?>profiles/update/<?php echo $user['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" alt="" /></a><?php } ?></div>


<div style="width: 150px; text-shadow: 0px 1px 0px #000; text-align: right; float: right; font-size: 10px; color: #AAA; letter-spacing: 0px; line-height: 25px;">Last alive: <?php echo date('jS F', $user['lastactive']); ?></div>

<div style="clear: both; float: left; font-size: 12px; text-shadow: 0px 1px 0px #000; line-height: 15px;">
<?php if (!empty($user['description'])) { ?>
<span title="<?php echo html::specialchars($user['description']); ?>"><?php echo text::limit_words($user['description'], 17, '...'); ?></span>
<?php } ?>
</div>

	<div style="text-align: right; float: right; height: 25px; line-height: 25px; font-size: 12px; letter-spacing: 0px; color: #888; text-shadow: 0px 1px 0px #000;">
<?php if(!empty($user['email']) && $user['email_public'] == 1) { ?><a href="mailto:<?php echo $user['email']; ?>"><img src="<?php echo url::base(); ?>images/icons/email.png" /></a>&nbsp;<?php } ?>
<?php if(!empty($user['website'])) { ?><a href="<?php echo $user['website']; ?>"><img src="<?php echo url::base(); ?>images/icons/world_link.png" /></a>&nbsp;<?php } ?>
<?php if(!empty($user['msn'])) { ?><img src="<?php echo url::base(); ?>images/icons/msn.png" title="<?php echo $user['msn']; ?>" alt="MSN" />&nbsp;<?php } ?>
<?php if(!empty($user['gtalk'])) { ?><img src="<?php echo url::base(); ?>images/icons/gtalk.png" title="<?php echo $user['gtalk']; ?>" alt="gtalk" />&nbsp;<?php } ?>
<?php if(!empty($user['yahoo'])) { ?><img src="<?php echo url::base(); ?>images/icons/yahoo.png" title="<?php echo $user['yahoo']; ?>" alt="yahoo" />&nbsp;<?php } ?>
<?php if(!empty($user['skype'])) { ?><img src="<?php echo url::base(); ?>images/icons/skype.png" title="<?php echo $user['skype']; ?>" alt="skype" />&nbsp;<?php } ?>
<?php if(empty($user['email']) && empty($user['website']) && empty($user['msn']) && empty($user['gtalk']) && empty($user['yahoo']) && empty($user['skype'])) { ?><img src="<?php echo url::base(); ?>images/icons/status_online.png" alt="" /> No contact information<?php } ?>
	</div>
	<div style="clear:both;"></div>
</div>
</h2>
