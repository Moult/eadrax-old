<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>.jpg" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/noavatar.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
<?php } ?>

<h2 style="margin-left: 5px; float: left; height: 82px; width: 590px;">
	<div style="height: 30px;"><?php echo $user['username']; ?>'s projects and latest updates</div>
	<div style="height: 30px; line-height: 30px; font-size: 15px; letter-spacing: -1px; color: #888;">
<?php if (!empty($age)) { ?>
<?php echo $age; ?> year old
<?php if ($user['gender'] != 'Confused') { ?>
<?php echo strtolower($user['gender']); ?> 
<?php } else { ?>
gender-confused person
<?php } ?>
<?php if (!empty($user['location'])) { ?>
living in <?php echo $user['location']; ?>
<?php } ?>
<?php } else { ?>
A sociopath who hasn't yet updated his profile information
<?php } ?>
<?php if ($this->uid == $user['id']) { ?> <a href="<?php echo url::base(); ?>profiles/update/<?php echo $user['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" alt="" /></a><?php } ?></div>
	<div style="height: 25px; line-height: 25px; font-size: 12px; letter-spacing: -1px; color: #888;">
<?php if(!empty($user['email'])) { ?><a href="mailto:<?php echo $user['email']; ?>"><img src="<?php echo url::base(); ?>images/icons/email.png" /></a>&nbsp;<?php } ?>
<?php if(!empty($user['website'])) { ?><a href="<?php echo $user['website']; ?>"><img src="<?php echo url::base(); ?>images/icons/world_link.png" /></a>&nbsp;<?php } ?>
<?php if(!empty($user['msn'])) { ?><img src="<?php echo url::base(); ?>images/icons/msn.png" title="<?php echo $user['msn']; ?>" alt="MSN" />&nbsp;<?php } ?>
<?php if(!empty($user['gtalk'])) { ?><img src="<?php echo url::base(); ?>images/icons/gtalk.png" title="<?php echo $user['gtalk']; ?>" alt="gtalk" />&nbsp;<?php } ?>
<?php if(!empty($user['yahoo'])) { ?><img src="<?php echo url::base(); ?>images/icons/yahoo.png" title="<?php echo $user['yahoo']; ?>" alt="yahoo" />&nbsp;<?php } ?>
<?php if(!empty($user['skype'])) { ?><img src="<?php echo url::base(); ?>images/icons/skype.png" title="<?php echo $user['skype']; ?>" alt="skype" />&nbsp;<?php } ?>
<?php if(empty($user['email']) && empty($user['website']) && empty($user['msn']) && empty($user['gtalk']) && empty($user['yahoo']) && empty($user['skype'])) { ?><img src="<?php echo url::base(); ?>images/icons/status_online.png" alt="" /> No contact information is available<?php } ?>
	</div>
</h2>

<div style="width: 150px; text-align: right; float: right; font-size: 14px; color: #AAA; letter-spacing: -1px; line-height: 40px;">Last active: <?php echo date('jS F', $user['lastactive']); ?></div>

<?php if (!empty($user['description'])) { ?>
<div style="clear: both; background-color: #FFF; border: 1px solid #888; padding: 10px; margin-bottom: 10px;">
<?php echo $user['description']; ?>
</div>
<?php } ?>

<div style="clear: both; overflow: hidden; background: #EEE; border: 1px solid #AAA; padding-bottom: 5px;">
<?php echo $markup; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$("#expand").click(function(){
<?php foreach ($pid_array as $pid) { ?>
		$("#slider<?php echo $pid; ?>").slideToggle("slow");
		$("#summary<?php echo $pid; ?>").slideToggle("slow");
		$("#information<?php echo $pid; ?>").slideToggle("slow");
		if ($("#section_top_left<?php echo $pid; ?>").hasClass('tall')) {
			$("#section_divider<?php echo $pid; ?>").css("background-color", "#E3F8FF");
			$("#section_top_right<?php echo $pid; ?>").animate({height: '100'});
			$("#section_top_left<?php echo $pid; ?>").animate({height: '100'}).removeClass('tall');
		} else {
			$("#section_divider<?php echo $pid; ?>").css("background-color", "white");
			$("#section_top_right<?php echo $pid; ?>").animate({height: '230'});
			$("#section_top_left<?php echo $pid; ?>").animate({height: '230'}).addClass('tall');
		}
<?php } ?>
		if ($("#expand").hasClass('expand')) {
			$("#expand").attr("src","<?php echo url::base(); ?>images/collapse.png").removeClass('expand');
		} else {
			$("#expand").attr("src","<?php echo url::base(); ?>images/expand.png").addClass('expand');
		}
	});
});
</script>

<div style="text-align: center;">
<img src="<?php echo url::base(); ?>images/expand.png" id="expand" class="expand" alt="expand all projects" style="position: relative; top: 19px;" />
</div>
