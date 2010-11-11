<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>.jpg" class="icon" alt="" style="border: 1px solid #D8D8D8; padding: 1px; float: left;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/noavatar.png" class="icon" alt="" style="border: 1px solid #D8D8D8; padding: 1px; float: left;" />
<?php } ?>

<h2 style="margin-left: 6px; float: left; height: 82px; width: 740px;">
	<div style="float: left; height: 30px;"><?php echo $user['username']; ?>'s WIPspace</div>
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
	<div style="float: right; margin-top: -5px;">
	<img src="<?php echo url::base(); ?>images/icons/photos.png" alt="" />
		<ul style="margin-left: 0px; display: inline;">
			<li style="width: 110px; display: inline;">
				<input style="width: 110px; letter-spacing: 0px;" type="button" onclick="parent.location='<?php echo url::base(); ?>profiles/projects/<?php echo $uid; ?>/'" value="View projects" />
			</li>
		</ul>
	</div>
	<?php } ?>
<div style="clear: both; line-height: 25px; font-size: 12px; letter-spacing: 0px; color: #888; border-top: 1px solid #999; border-bottom: 1px solid #999; border-left: 0px; border-right: 0px; background-position: top; background-repeat: repeat-x; background-color: #EEE; padding: 8px; padding-top: 2px; padding-bottom: 0px; margin-bottom: 0px;">
<div style="float: left;">
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


<div style="width: 150px; text-align: right; float: right; font-size: 10px; color: #AAA; letter-spacing: 0px; line-height: 25px;">Last active: <?php echo date('jS F', $user['lastactive']); ?></div>

<div style="clear: both; float: left; font-size: 12px; text-shadow: 0px 1px 0px #FFF; line-height: 15px;">
<?php if (!empty($user['description'])) { ?>
<span title="<?php echo html::specialchars($user['description']); ?>"><?php echo text::limit_words($user['description'], 17, '...'); ?></span>
<?php } ?>
</div>

	<div style="text-align: right; float: right; height: 25px; line-height: 25px; font-size: 12px; letter-spacing: 0px; color: #888;">
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
