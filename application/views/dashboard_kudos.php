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
