Total people tracking me: <?php echo $total; ?><br />
Who is tracking me?<br />
<?php
foreach ($track_list as $tracker)
{
	echo $tracker[1] .' with the uid '. $tracker[0] .'<br />';
}
