<h2>Stats at a glance.</h2>

<div style="clear: left; float: left; width: 413px;">
Your update activity over time<br />
<img src="<?php echo url::site('dashboard/update_activity/'. $this->uid .'/'); ?>">
</div>

<div style="float: left; width: 413px;">
Chart of project popularity based on subscribers:<br />
<?php
if (isset($popular_error))
{
	echo 'not enough data to draw the graph';
}
else
{
?>
<img src="<?php echo url::site('dashboard/popular_project_subscribers/'. $this->uid .'/'); ?>">
<?php } ?>
</div>

<div style="clear: left; float: left; width: 413px;">
How many times your stuff has been viewed.<br />
<img src="<?php echo url::site('dashboard/view_activity/'. $this->uid .'/'); ?>"></div>

<div style="float: left; width: 413px;">
Total kudoses: <?php echo $total; ?><br />
<?php if ($total == 0)
{
echo 'not enough data to draw the graph';
}
else
{
?>
<img src="<?php echo url::site('dashboard/popular_project_kudos/'. $this->uid .'/'); ?>">
<?php } ?>
</div>

<div style="clear: left; float: left; width: 413px;">
Your comment activity over time<br />
<img src="<?php echo url::site('dashboard/comment_activity/'. $this->uid .'/'); ?>">
</div>

<div style="float: left; width: 413px;">
Activity per project:<br />
<?php if (isset($activity_error))
{ echo 'not enough data to draw the graph'; }
else { ?>
	<img src="<?php echo url::site('dashboard/projects_activity/'. $this->uid); ?>">
<?php } ?>
</div>
