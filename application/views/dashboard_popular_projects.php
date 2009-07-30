Chart of project popularity based on subscribers:<br />
<?php
if (isset($error))
{
	echo 'not enough data to draw the graph';
}
else
{
?>
<img src="<?php echo url::site('dashboard/popular_project_subscribers/'. $this->uid .'/'); ?>">
<?php } ?>
