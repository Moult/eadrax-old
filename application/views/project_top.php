<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>_small.jpg" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/icons/app_48.png" class="icon" alt="" style="padding: 1px; float: left;" />
<?php } ?>

<h2 style="margin-left: 6px; float: left; height: 50px; width: 770px;">
	<div style="float: left; height: 30px;"><?php echo $user['username']; ?>'s Projects</div>
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
<?php if (isset($featured_project_information)) { ?>
<div style="clear: both; line-height: 23px; font-size: 15px; letter-spacing: -1px; color: #888; border-top: 1px solid #999; border-left: 0px; border-right: 0px; background-image: url(<?php echo url::base(); ?>images/formbg.gif); background-position: top; background-repeat: repeat-x; background-color: #D8D8D8; padding: 8px; padding-top: 2px; padding-bottom: 4px; margin-bottom: 10px;">
<div style="float: left;">
<strong>Featuring <?php echo $featured_project_information['name']; ?></strong> - <?php echo $featured_project_information['summary']; ?>
</div>


<div style="width: 150px; text-align: right; float: right; font-size: 14px; color: #AAA; letter-spacing: -1px; line-height: 23px;">Last active: <?php echo date('jS F', $user['lastactive']); ?></div>
	<div style="clear: both;"></div>

</div>
<?php } ?>
</h2>


	<?php if (!empty($featured_filename)) { ?>
<div style="clear: both; border-bottom: 1px solid #999; background-color: #E2E2E2; padding: 10px; padding-top: 5px; padding-bottom: 4px; margin-bottom: 20px; font-size: 13px; text-shadow: 0px 1px 0px #FFF;">

	<div style="height: 250px; width: 808px; margin-left: 1px; background-color: #FFF; padding: 1px; border: 1px solid #999; margin-bottom: 5px;">
	<a href="<?php echo url::base(); ?>projects/view/<?php echo $user['id']; ?>/<?php echo $featured_project_information['id']; ?>/"><img style="background-image: url(<?php echo url::base(); ?>uploads/files/<?php echo $featured_filename; ?>); background-position: 0px -<?php echo $featured_height; ?>px;" src="<?php echo url::base(); ?>images/featured_overlay.png" alt="">
	</a>
	</div>
</div>

	<?php } else { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#content_top").css({'min-height': '60px'});
	$("#content_top").animate({height: '60px'});
	$("#section_divider").css({'background-color': '#E3F8FF'});
	$("#content_top_left").animate({height: '60px'});
	$("#content_top_right").animate({height: '60px'});
});
</script>
	<?php } ?>


<?php if (!empty($pid_array)) { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#expand").click(function(){
<?php foreach ($pid_array as $pid) { ?>
		if ($("#expand").hasClass('expand')) {
			$("#section_top<?php echo $pid; ?>").css({'height': ''});
			$("#section_divider<?php echo $pid; ?>").css("background-color", "white");
			$("#section_divider<?php echo $pid; ?>").animate({height: '24px'});
			$("#section_top_right<?php echo $pid; ?>").animate({height: '230px'});
			if (!$("#section_top_left<?php echo $pid; ?>").hasClass('tall')) {
				$("#section_top_left<?php echo $pid; ?>").animate({height: '230px'}).addClass('tall');
				$("#slider<?php echo $pid; ?>").slideToggle("slow");
			}
		} else {
			$("#section_top<?php echo $pid; ?>").css({'min-height': '50px'});
			$("#section_top<?php echo $pid; ?>").animate({height: '50px'});
			$("#section_divider<?php echo $pid; ?>").css("background-color", "#E3F8FF");
			$("#section_divider<?php echo $pid; ?>").animate({height: '18px'});
			$("#section_top_right<?php echo $pid; ?>").animate({height: '60px'});
			if ($("#section_top_left<?php echo $pid; ?>").hasClass('tall')) {
				$("#section_top_left<?php echo $pid; ?>").animate({height: '60px'}).removeClass('tall');
				$("#slider<?php echo $pid; ?>").slideToggle("slow");
			}
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
