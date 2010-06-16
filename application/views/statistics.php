<h2>
	<img src="<?php echo url::base(); ?>images/icons/pie_chart_48.png" class="icon" alt="" />
	Stats at a glance.
</h2>


<?php if (isset($nostats)) { ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".single_image").mouseenter(function(){
		$(".single_cite").slideToggle("fast");
	});
	$(".single_image").mouseleave(function(){
		$(".single_cite").slideToggle("fast");
	});
});
</script>
<?php } ?>

<div class="single_image" style="clear: both; line-height: 30px; font-size: 15px; letter-spacing: -1px; color: #888; border-top: 1px solid #999; border-left: 0px; border-right: 0px; background-image: url(<?php echo url::base(); ?>images/formbg.gif); background-position: top; background-repeat: repeat-x; background-color: #D8D8D8; padding: 8px; padding-top: 2px; padding-bottom: 8px; margin-bottom: 10px; text-shadow: 0px 1px 0px #FFF;">
	<span style="float: left;">Update and View Activity</span>
	<span style="float: right;">Project Statistics</span>
	<div style="clear: both; background-color: #FFF; border-top: 1px solid #888; padding: 10px; margin-bottom: 10px; padding-bottom: 10px; background-image: url('<?php echo url::base(); ?>images/comment_divide.png'); background-repeat: repeat-x; background-position: bottom;">
		<div style="float: left;">
			<img src="http://chart.apis.google.com/chart?cht=lc&chs=390x200&chd=<?php echo $line_chd; ?>&chds=<?php echo $line_chds; ?>&chxt=x,y,r,x&chxr=<?php echo $line_chxr; ?>&chxl=<?php echo $line_chxl; ?>&chco=2E97E0,E0632D&chxs=1,E0632D,10|2,2E97E0,10|3,000000,12|0,,9,0,lt&chg=0,20,1,2&chf=c,ls,90,EEEEEE,0.2,FFFFFF,0.2&chls=4|5">
		</div>
		<div style="float: right;">
		<img src="http://chart.apis.google.com/chart?cht=bvs&chs=390x200&chd=<?php echo $bar_chd; ?>&chxr=<?php echo $bar_chxr; ?>&chds=<?php echo $bar_chds; ?>&chco=E0632D,EA983A,EAD444&chbh=<?php echo $bar_chbh; ?>&chxt=x,y,x&chxl=<?php echo $bar_chxl; ?>&chxs=2,000000,10,0,lt|0,000000,10,0&chg=0,20,1,2&chf=c,ls,90,EEEEEE,0.2,FFFFFF,0.2">
		</div>
		<div style="clear: both;"></div>
	</div>
		<cite class="single_cite" style="width: 794px; text-shadow: none; bottom: 27px; padding-bottom: 15px; padding-top: 0px; font-size: 12px; letter-spacing: 0px; text-align: center;">Not seeing much? Try being a little bit more active.</cite>
	<div style="width: 7px; height: 7px; background-color: #E0632D; float: left;"></div>
	<span style="float: left; font-size: 11px; line-height: 8px; letter-spacing: 0px; margin-left: 5px; margin-right: 10px;">UPDATES</span>
	<div style="width: 7px; height: 7px; background-color: #2E97E0; float: left;"></div>
	<span style="float: left; font-size: 11px; line-height: 8px; letter-spacing: 0px; margin-left: 5px; margin-right: 10px;">VIEWS</span>
	<span style="float: right; font-size: 11px; line-height: 8px; letter-spacing: 0px; margin-left: 5px; margin-right: 10px;">SUBSCRIPTIONS</span>
	<div style="width: 7px; height: 7px; background-color: #EAD444; float: right;"></div>
	<span style="float: right; font-size: 11px; line-height: 8px; letter-spacing: 0px; margin-left: 5px; margin-right: 10px;">KUDOS</span>
	<div style="width: 7px; height: 7px; background-color: #EA983A; float: right;"></div>
	<span style="float: right; font-size: 11px; line-height: 8px; letter-spacing: 0px; margin-left: 5px; margin-right: 10px;">UPDATES</span>
	<div style="width: 7px; height: 7px; background-color: #E0632D; float: right;"></div>
	<div style="clear: both;"></div>
</div>


<!--
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
-->
