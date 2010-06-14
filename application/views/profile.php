<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>.jpg" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/noavatar.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
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
<div style="clear: both; line-height: 30px; font-size: 15px; letter-spacing: -1px; color: #888; border-top: 1px solid #999; border-left: 0px; border-right: 0px; background-image: url(<?php echo url::base(); ?>images/formbg.gif); background-position: top; background-repeat: repeat-x; background-color: #D8D8D8; padding: 8px; padding-top: 2px; padding-bottom: 4px; margin-bottom: 10px;">
<div style="float: left;">
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


<div style="width: 150px; text-align: right; float: right; font-size: 14px; color: #AAA; letter-spacing: -1px; line-height: 30px;">Last active: <?php echo date('jS F', $user['lastactive']); ?></div>

	<div style="clear: both; height: 25px; line-height: 25px; font-size: 12px; letter-spacing: 0px; color: #888;">
<?php if(!empty($user['email'])) { ?><a href="mailto:<?php echo $user['email']; ?>"><img src="<?php echo url::base(); ?>images/icons/email.png" /></a>&nbsp;<?php } ?>
<?php if(!empty($user['website'])) { ?><a href="<?php echo $user['website']; ?>"><img src="<?php echo url::base(); ?>images/icons/world_link.png" /></a>&nbsp;<?php } ?>
<?php if(!empty($user['msn'])) { ?><img src="<?php echo url::base(); ?>images/icons/msn.png" title="<?php echo $user['msn']; ?>" alt="MSN" />&nbsp;<?php } ?>
<?php if(!empty($user['gtalk'])) { ?><img src="<?php echo url::base(); ?>images/icons/gtalk.png" title="<?php echo $user['gtalk']; ?>" alt="gtalk" />&nbsp;<?php } ?>
<?php if(!empty($user['yahoo'])) { ?><img src="<?php echo url::base(); ?>images/icons/yahoo.png" title="<?php echo $user['yahoo']; ?>" alt="yahoo" />&nbsp;<?php } ?>
<?php if(!empty($user['skype'])) { ?><img src="<?php echo url::base(); ?>images/icons/skype.png" title="<?php echo $user['skype']; ?>" alt="skype" />&nbsp;<?php } ?>
<?php if(empty($user['email']) && empty($user['website']) && empty($user['msn']) && empty($user['gtalk']) && empty($user['yahoo']) && empty($user['skype'])) { ?><img src="<?php echo url::base(); ?>images/icons/status_online.png" alt="" /> No contact information is available<?php } ?>
	</div>
</div>
</h2>


<div style="clear: both; border-bottom: 1px solid #999; background-color: #D8D8D8; padding: 10px; padding-top: 5px; padding-bottom: 8px; margin-bottom: 10px; font-size: 13px; text-shadow: 0px 1px 0px #FFF;">
<?php if (!empty($user['description'])) { ?>
<?php echo $user['description']; ?>
<?php } else { ?>
	Normally a descriptive paragraph about the person would be placed here, but since there is no other information describing <?php echo $user['username']; ?> you'll just have to use your imagination. Oh well.
<?php } ?>
</div>


<?php if (!empty($featured_filename)) { ?>
<div style="padding: 8px; padding-top: 2px; padding-bottom: 4px; margin-bottom: 10px; text-align: center;">
<p style="height: 30px; line-height: 30px; padding: 0px; margin: 0px;">
<strong>Featuring <?php echo $featured_project_information['name']; ?></strong> - <?php echo $featured_project_information['summary']; ?>
</p>

<div style="height: 250px; width: 808px; margin-left: 1px; background-color: #FFF; padding: 1px; border: 1px solid #999; margin-bottom: 5px;">
<a href="<?php echo url::base(); ?>projects/view/<?php echo $user['id']; ?>/<?php echo $featured_project_information['id']; ?>/"><img style="background-image: url(<?php echo url::base(); ?>uploads/files/<?php echo $featured_filename; ?>); background-position: 0px -<?php echo $featured_height; ?>px;" src="<?php echo url::base(); ?>images/featured_overlay.png" alt="">
</a>
</div>

</div>
<?php } ?>

<?php if (!empty($pid_array)) { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#expand").click(function(){
<?php foreach ($pid_array as $pid) { ?>
		$("#slider<?php echo $pid; ?>").slideToggle("slow");
		$("#summary<?php echo $pid; ?>").slideToggle("slow");
		$("#information<?php echo $pid; ?>").slideToggle("slow");
		if ($("#section_top_left<?php echo $pid; ?>").hasClass('tall')) {
			$("#section_divider<?php echo $pid; ?>").css("background-color", "#E3F8FF");
			$("#section_divider<?php echo $pid; ?>").animate({height: '18'});
			$("#section_top_right<?php echo $pid; ?>").animate({height: '100'});
			$("#section_top_left<?php echo $pid; ?>").animate({height: '100'}).removeClass('tall');
		} else {
			$("#section_divider<?php echo $pid; ?>").css("background-color", "white");
			$("#section_divider<?php echo $pid; ?>").animate({height: '24'});
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

<div style="text-align: center; position: absolute; left: 130px; bottom: 0px;">
<img src="<?php echo url::base(); ?>images/expand.png" id="expand" class="expand" alt="expand all projects" style="position: relative; top: 19px;" />
</div>
<?php } ?>
