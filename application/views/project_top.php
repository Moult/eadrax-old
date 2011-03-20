<?php if (!empty($user['avatar'])) { ?>
<img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $user['avatar']; ?>_small.jpg" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" />
<?php } else { ?>
<img src="<?php echo url::base(); ?>images/icons/app_48.png" class="icon" alt="" style="padding: 1px; float: left;" />
<?php } ?>

<h2 style="margin-left: 6px; float: left; height: 50px; width: 770px;">
	<div style="float: left; height: 30px;"><?php echo $user['username']; ?>'s Projects</div>
	<?php if (!empty($browseby)) { ?>

	<div id="filter" style="margin: none; clear: none; float: right; text-shadow: none; font-size: 10px; letter-spacing: 0px;">
	<ul class="block_links" style="float: left;">
	<li style="margin: 0px;"><a href="<?Php echo url::base(); ?>profiles/view/<?php echo $user['username']; ?>/" class="block">Back to <?php echo $user['username']; ?>'s WIPSpace</a></li>
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
</h2>

<script type="text/javascript">
$(document).ready(function() {
	$("#content_top").css({'min-height': '60px'});
	$("#content_top").animate({height: '60px'});
	$("#section_divider").css({'background-color': '#E3F8FF'});
	$("#content_top_left").animate({height: '60px'});
	$("#content_top_right").animate({height: '60px'});
});
</script>

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
