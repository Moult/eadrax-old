<h2>
	<?php if (!empty($project['icon'])) { ?>
	<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
	<?php } else { ?>
	<img src="<?php echo url::base(); ?>images/icons/coffee_mug.png" width="48" height="48" class="icon" alt="" />
	<?php } ?>
	<?php echo $project['name']; ?>'s Timeline <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>
</h2>

<?php echo $markup; ?>
