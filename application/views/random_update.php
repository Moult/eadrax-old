<h2>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" width="48" height="48" class="icon" alt="" />
	<?php echo $user['username']; ?>'s other WIPs
</h2>

<div style="text-align: center; border: 0px solid #F00; overflow: hidden; width: 820px; margin-left: auto; margin-right: auto;">

<?php foreach ($random_data as $wip) { ?>
	<div style="float: left; width: 150px; text-align: center; margin-left: 7px; margin-right: 7px;">
		<div style="height: 100px; margin-bottom: 5px; position: relative; text-align: center;">
			<div style="width: 150px; position: absolute; top: 50px; height: <?php echo $wip['thumb_height']; ?>px; margin-top: -<?php echo $wip['thumb_offset']; ?>px; text-align: center;">
				<a href="<?php echo url::base() .'updates/view/'. $wip['id']; ?>"><img src="<?php echo $wip['filename']; ?>" title="<?php echo $wip['summary']; ?>"></a>
			</div>
		</div>
		<?php if ($wip['pid'] != 1) { ?><a href="<?php echo url::base() .'projects/'. $wip['pid'] .'/'; ?>"><?php } ?><strong><?php echo $wip['project_name']; ?></strong><?php if ($wip['pid'] != 1) { ?></a><?php } ?>
	</div>
<?php } ?>

</div>
