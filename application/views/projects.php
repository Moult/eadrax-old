<h2 style="float: left; margin-top: 0px; margin: 0px;">
	<div style="float: left;">
		<?php if (!empty($project['icon'])) { ?>
		<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #555;" />
		<?php } else { ?>
		<img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #555;" />
		<?php } ?>
	</div>

	<div style="float: left; margin-left: 5px;">
		<a href="<?php echo url::base(); ?>projects/view/<?php echo $uid; ?>/<?php echo $project['id']; ?>/" style="font-size: 16px; letter-spacing: 0px; color: #FF7600; text-decoration: none;"><?php echo $project['name']; ?></a> <?php if ($uid != $project['uid'] && $project['uid'] != 1) { ?><img src="<?php echo url::base(); ?>images/icons/user_edit.png" class="icon" alt="Contributor" title="Contributor" /><?php } ?> <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>

		<div style="font-weight: bold; color: #555; letter-spacing: 0px; font-size: 12px;">
			<?php echo $project['summary']; ?>
		</div>
	</div>
</h2>

<div style="float: right; margin-left: -15px; margin-top: 17px;">
		<ul>
			<li style="width: 70px; display: inline;">
				<input id="expand<?php echo $project['id']; ?>" style="width: 70px;" type="button" value="Peek" />
			</li>
		</ul>
</div>

<div style="float: right; margin-left: 5px; margin-top: 14px;">
	<?php echo $mini_markup; ?>
</div>


<?php if ($this->uri->segment(1) == 'updates') { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#expand<?php echo $project['id']; ?>").click(function(){
		$("#slider<?php echo $project['id']; ?>").slideToggle("slow");
	});
});
</script>
<?php } else { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#expand<?php echo $project['id']; ?>").click(function(){
		$("#slider<?php echo $project['id']; ?>").slideToggle("slow");
		if ($("#section_top<?php echo $project['id']; ?>").hasClass('tall')) {
			$("#section_top<?php echo $project['id']; ?>").removeClass('tall');
		} else {
			$("#section_top<?php echo $project['id']; ?>").css({'height': ''}).addClass('tall');
		}
	});
});
</script>
<?php } ?>

<div id="slider<?php echo $project['id']; ?>" style="width: 830px; display: none;">

	<div style="clear: both;">
		<div style="float: left; font-size: 12px; color: #AAA; margin-bottom: 5px; margin-top: 5px;">
				Category: <em><?php echo $categories[$project['cid']]; ?></em> <?php if (!empty($project['website'])) { echo ' (<a href="'. $project['website'] .'">www</a>)'; } ?>
		</div>

		<div style="float: right; font-size: 12px; color: #AAA; text-align: right; margin-bottom: 5px; margin-top: 5px;">
			Last updated <?php echo date('jS F Y', strtotime($project['logtime'])); ?>
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
