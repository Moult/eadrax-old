<h2>Welcome <?php echo $user->username; ?></h2>
<ul>
	<li><a href="<?php echo url::site('profiles/update/' . $user->id); ?>">Update Information</a></li>
	<li><a href="<?php echo url::site('profiles/change_password/' . $user->id); ?>">Change Password</a></li>
	<li><a href="<?php echo url::site('users/logout'); ?>">Logout</a></li>
</ul>

This page is under construction. Sorry if it looks ugly. On this page we will show a chronological timeline of your project progress. Of course we'll also show your user information.<br /><br />

Your projects and updates...in a very crude way:<br />
<?php
foreach ($projects as $pid => $p_name) {
	echo '<strong>'. $p_name .'</strong><br />';
	if (empty($project_updates[$pid])) {
		echo 'no updates in this project';
	} else {
		foreach ($project_updates[$pid] as $upid => $up_name) {
			echo '<a href="'. url::base() .'updates/view/'. $upid .'/">'. $up_name .'</a><br />';
		}
	}
	echo '<br />';
}
?>
