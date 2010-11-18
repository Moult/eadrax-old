<h2>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" width="48" height="48" class="icon" alt="" />
	<?php if(!empty($user)) { ?>
	<?php echo $user['username']; ?>'s Random WIPs
	<?php } else { ?>
	Lights are on but nobody's home
	<?php } ?>
</h2>

<?php if(empty($user)) { ?>

<p style="text-align: center; font-size: 20px; color: #555; line-height: 50px;">
	It looks as though no updates have been added yet.<br />
	<a href="<?php echo url::base(); ?>updates/add/">Adding some</a> would probably be a good thing to do :)
</p>

<?php } else { ?>

<div style="text-align: center; border: 0px solid #F00; overflow: hidden; width: 820px; margin-left: auto; margin-right: auto;">

<?php $i = 0; foreach ($random_data as $wip) { $i++; ?>
<div style="float: left; width: 150px; height: 180px; text-align: center; margin-left: 6px; margin-right: 7px;<?php if ($i != 5) { ?> border-right: 1px solid #EEE;<?php } ?>">
		<p style="font-size: 10px; color: #777; margin-right: 15px;">
			<?php if ($wip['pid'] != 1) { ?><a href="<?php echo url::base() .'projects/view/'. $wip['uid'] .'/'. $wip['pid'] .'/'; ?>"><?php } ?><strong><?php echo $wip['project_name']; ?></strong><?php if ($wip['pid'] != 1) { ?></a><?php } ?>
		</p>

		<div style="display: table-cell; height: 100px; margin-bottom: 5px; text-align: center;">
			<div style="border: 0px solid #F00; width: 140px; position: relative; top: 50px; height: <?php echo $wip['thumb_height']; ?>px; margin-top: -<?php echo $wip['thumb_offset']; ?>px; text-align: center;">
				<a href="<?php echo url::base() .'updates/view/'. $wip['id']; ?>"><img src="<?php echo $wip['filename0']; ?>" title="<?php echo $wip['summary']; ?>" alt="update icon" <?php if (!strpos($wip['filename0'], 'images/icons')) { echo 'style="-moz-box-shadow: 1px 1px 3px #555; -webkit-box-shadow: 1px 1px 3px #555; box-shadow: 1px 1px 3px #555; padding: 2px; background-color: #FFF;"'; } ?> /></a>
			</div>
		</div>

		<p style="word-wrap: break-word; width: 140px;">
			<a href="<?php echo url::base() .'updates/view/'. $wip['id']; ?>" style="text-decoration: none;"><?php echo $wip['summary']; ?></a>
		</p>

	</div>
<?php } ?>

</div>

<?php } ?>
