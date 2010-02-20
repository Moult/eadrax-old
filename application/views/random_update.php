<h2>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" width="48" height="48" class="icon" alt="" />
	<?php echo $user['username']; ?>'s other WIPs
</h2>

<div style="text-align: center; border: 0px solid #F00; overflow: hidden; width: 820px; margin-left: auto; margin-right: auto;">

<?php $i = 0; foreach ($random_data as $wip) { $i++; ?>
<div style="float: left; width: 150px; height: 180px; text-align: center; margin-left: 6px; margin-right: 7px;<?php if ($i != 5) { ?> border-right: 1px solid #EDEDED;<?php } ?>">
		<p style="font-size: 10px; color: #777;">
			<?php if ($wip['pid'] != 1) { ?><a href="<?php echo url::base() .'projects/'. $wip['pid'] .'/'; ?>"><?php } ?><strong><?php echo $wip['project_name']; ?></strong><?php if ($wip['pid'] != 1) { ?></a><?php } ?>
		</p>

		<div style="height: 100px; margin-bottom: 5px; position: relative; text-align: center;">
			<div style="width: 140px; position: absolute; top: 50px; height: <?php echo $wip['thumb_height']; ?>px; margin-top: -<?php echo $wip['thumb_offset']; ?>px; text-align: center;">
				<a href="<?php echo url::base() .'updates/view/'. $wip['id']; ?>"><img src="<?php echo $wip['filename0']; ?>" title="<?php echo $wip['summary']; ?>" alt="update icon" /></a>
			</div>
		</div>

		<p style="word-wrap: break-word; width: 140px;">
			<a href="<?php echo url::base() .'updates/view/'. $wip['id']; ?>" style="text-decoration: none;"><?php echo $wip['summary']; ?></a>
		</p>

	</div>
<?php } ?>

</div>
