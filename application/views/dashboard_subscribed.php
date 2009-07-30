Total people subscribed to all my projects: <?php echo $total; ?><br />
Who is subscribed to my projects?<br />
<?php
foreach ($project_subscribe_list as $pid => $project)
{
	echo 'for the project '. $project[0] .' with id '. $pid .' there are '. $project[1] .' people subscribed who are ...<br />';
	if (!empty($project[2]))
	{
		foreach($project[2] as $subscriber)
		{
			echo $subscriber[1] .' with the uid '. $subscriber[0] .'<br />';
		}
	}
	else
	{
		echo '... there are no subscribers<br />';
	}
}
