Activity per project:<br />
<?php if (isset($error))
{ echo 'not enough data to draw the graph'; }
else { ?>
	<img src="<?php echo url::site('dashboard/projects_activity/'. $this->uid); ?>">
<?php } ?>
