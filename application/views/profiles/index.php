<h2>Welcome <?php echo $user->username; ?></h2>
<ul>
	<li><a href="<?php echo url::site('profiles/update/' . $user->id); ?>">Update Information</a></li>
	<li><a href="<?php echo url::site('profiles/change_password/' . $user->id); ?>">Change Password</a></li>
	<li><a href="<?php echo url::site('users/logout'); ?>">Logout</a></li>
</ul>