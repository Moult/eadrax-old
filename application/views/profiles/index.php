<h2>Welcome <?php echo $user->username; ?></h2>
<ul>
	<li><a href="<?php echo url::site('profiles/update/' . $user->id); ?>">Update Information</a></li>
	<li><a href="<?php echo url::site('profiles/change_password/' . $user->id); ?>">Change Password</a></li>
	<li><a href="<?php echo url::site('users/logout'); ?>">Logout</a></li>
</ul>

This page is under construction. Sorry if it looks ugly. On this page we will show a chronological timeline of your project progress. Of course we'll also show your user information.<br /><br />


