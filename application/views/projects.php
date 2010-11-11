<h2 style="float: left;">
	<div style="float: left;">
		<?php if (!empty($project['icon'])) { ?>
		<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
		<?php } else { ?>
		<img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
		<?php } ?>
	</div>

	<div style="float: left; margin-left: 5px;">
		<a href="<?php echo url::base(); ?>projects/view/<?php echo $uid; ?>/<?php echo $project['id']; ?>/" style="color: #FF7600; text-decoration: none;"><?php echo $project['name']; ?></a> <?php if ($uid != $project['uid'] && $project['uid'] != 1) { ?><img src="<?php echo url::base(); ?>images/icons/user_edit.png" class="icon" alt="Contributor" title="Contributor" /><?php } ?> <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>

		<div style="margin-top: 5px; font-weight: bold; color: #555; letter-spacing: 0px; font-size: 12px;">
			<?php echo $project['summary']; ?>
		</div>
	</div>
</h2>

<div style="float: right; margin-left: -15px; margin-top: 15px;">
		<ul>
			<li style="width: 70px; display: inline;">
				<input id="expand<?php echo $project['id']; ?>" style="width: 70px;" type="button" value="More" />
			</li>
		</ul>
</div>

<div style="float: right; margin-left: 5px; margin-top: 8px;">
	<?php echo $mini_markup; ?>
</div>


<?php if ($this->uri->segment(1) == 'updates') { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#content_top").css({'min-height': '50px'});
	$("#content_top").animate({height: '50px'});
	$("#section_divider<?php echo $project['id']; ?>").css({'background-color': '#E3F8FF'});
	$("#section_divider<?php echo $project['id']; ?>").animate({height: '18px'});
	$("#content_top_left").animate({height: '50px'});
	$("#content_top_right").animate({height: '50px'});

	$("#expand<?php echo $project['id']; ?>").click(function(){
		$("#slider<?php echo $project['id']; ?>").slideToggle("slow");
		if ($("#content_top_left").hasClass('tall')) {
			$("#content_top").animate({height: '50px'});
			$("#section_divider<?php echo $project['id']; ?>").css("background-color", "#E3F8FF");
			$("#section_divider<?php echo $project['id']; ?>").animate({height: '18'});
			$("#content_top_right").animate({height: '50'});
			$("#content_top_left").animate({height: '50'}).removeClass('tall');
		} else {
			$("#content_top").css({'height': ''});
			$("#section_divider<?php echo $project['id']; ?>").css("background-color", "white");
			$("#section_divider<?php echo $project['id']; ?>").animate({height: '24'});
			$("#content_top_right").animate({height: '230'});
			$("#content_top_left").animate({height: '230'}).addClass('tall');
		}
	});
});
</script>
<?php } else { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#expand<?php echo $project['id']; ?>").click(function(){
		$("#slider<?php echo $project['id']; ?>").slideToggle("slow");
		if ($("#section_top_left<?php echo $project['id']; ?>").hasClass('tall')) {
			$("#section_top<?php echo $project['id']; ?>").css({'min-height': '50px'});
			$("#section_top<?php echo $project['id']; ?>").animate({height: '50px'});
			$("#section_divider<?php echo $project['id']; ?>").css("background-color", "#E3F8FF");
			$("#section_divider<?php echo $project['id']; ?>").animate({height: '18'});
			$("#section_top_right<?php echo $project['id']; ?>").animate({height: '60'});
			$("#section_top_left<?php echo $project['id']; ?>").animate({height: '60'}).removeClass('tall');
		} else {
			$("#section_top<?php echo $project['id']; ?>").css({'height': ''});
			$("#section_divider<?php echo $project['id']; ?>").css("background-color", "white");
			$("#section_divider<?php echo $project['id']; ?>").animate({height: '24'});
			$("#section_top_right<?php echo $project['id']; ?>").animate({height: '230'});
			$("#section_top_left<?php echo $project['id']; ?>").animate({height: '230'}).addClass('tall');
		}
	});
});
</script>
<?php } ?>

<div id="slider<?php echo $project['id']; ?>" style="width: 830px; display: none;">

	<div style="clear: both;">
		<div style="float: left; font-size: 14px; color: #AAA; margin-bottom: 5px;">
				Category: <em><?php echo $categories[$project['cid']]; ?></em> <?php if (!empty($project['website'])) { echo ' (<a href="'. $project['website'] .'">www</a>)'; } ?>
		</div>

		<div style="float: right; font-size: 14px; color: #AAA; text-align: right; margin-bottom: 5px;">
			<?php echo date('jS F Y', strtotime($project['logtime'])); ?>
		</div>
	</div>

	<div style="clear: both; background-color: #FFF; border-top: 1px solid #888; padding: 10px; margin-bottom: 10px; padding-bottom: 1px; background-image: url('<?php echo url::base(); ?>images/comment_divide.png'); background-repeat: repeat-x; background-position: bottom;">
	<?php echo $description; ?>
	</div>

	<?php if (empty($timeline)) { ?>

	<div class="error_message">
		Oh no! There're no updates in this project just yet. You should add something, you know.
	</div>

	<?php } else { ?>
	<div class="scrollableWrapper" style="margin-top: 10px; margin-left: auto; margin-right: auto;">
		<a class="prev"></a>
		<div class="scrollable">
			<div class="thumbs">
				<?php echo $timeline; ?>
			</div>
		</div>
		<a class="next"></a>
	</div>
	<?php } ?>

</div>
