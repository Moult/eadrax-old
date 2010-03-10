<h2 style="float: left;">
	<?php if (!empty($project['icon'])) { ?>
	<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
	<?php } else { ?>
	<img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
	<?php } ?>
	<a href="<?php echo url::base(); ?>projects/view/<?php echo $uid; ?>/<?php echo $project['id']; ?>/" style="color: #FF7600; text-decoration: none;"><?php echo $project['name']; ?></a> <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>
</h2>

<div style="float: right; margin-top: 15px;">
    <ul>
        <li style="width: 70px; display: inline;">
			<input id="expand<?php echo $project['id']; ?>" style="width: 70px;" type="button" value="Preview" />
        </li>
        <li style="width: 70px; display: inline;">
			<input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>projects/view/<?php echo $uid ?>/<?php echo $project['id']; ?>/'" value="Detail" />
        </li>
    </ul>
</div>


<div style="clear: both;">
	<div style="float: left; font-size: 18px; letter-spacing: -1px; color: #AAA; margin-bottom: 5px;">
		<div id="summary<?php echo $project['id']; ?>">
			<?php echo $project['summary']; ?>
		</div>

		<div id="information<?php echo $project['id']; ?>" style="display: none;">
			Category: <em><?php echo $categories[$project['cid']]; ?></em> <?php if (!empty($project['website'])) { echo ' (<a href="'. $project['website'] .'">www</a>)'; } ?>
		</div>
	</div>

	<div style="float: right; font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;"><?php echo $project['logtime']; ?></div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$("#expand<?php echo $project['id']; ?>").click(function(){
		$("#slider<?php echo $project['id']; ?>").slideToggle("slow");
		$("#summary<?php echo $project['id']; ?>").slideToggle("slow");
		$("#information<?php echo $project['id']; ?>").slideToggle("slow");
		if ($("#section_top_left<?php echo $project['id']; ?>").hasClass('tall')) {
			$("#section_divider<?php echo $project['id']; ?>").css("background-color", "#E3F8FF");
			$("#section_top_right<?php echo $project['id']; ?>").animate({height: '100'});
			$("#section_top_left<?php echo $project['id']; ?>").animate({height: '100'}).removeClass('tall');
		} else {
			$("#section_divider<?php echo $project['id']; ?>").css("background-color", "white");
			$("#section_top_right<?php echo $project['id']; ?>").animate({height: '230'});
			$("#section_top_left<?php echo $project['id']; ?>").animate({height: '230'}).addClass('tall');
		}
	});
});
</script>

<div id="slider<?php echo $project['id']; ?>" style="width: 830px; display: none;">

	<div style="clear: both; background-color: #FFF; border: 1px solid #888; padding: 10px; margin-bottom: 10px; padding-bottom: 0px;">
	<?php echo $description; ?>
	</div>

	<?php if (empty($timeline)) { ?>

	<div style="border: 2px solid #800; background-color: #FDD; margin: 10px; padding: 10px;">
		Oh no! There's no updates in this project just yet. You should add something, you know.
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
