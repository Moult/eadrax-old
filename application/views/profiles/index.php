<h2><?php echo $user['username']; ?>'s Profile</h2>
<!--<ul>
	<li><a href="<?php echo url::site('profiles/update/' . $user['id']); ?>">Update Information</a></li>
	<li><a href="<?php echo url::site('profiles/change_password/' . $user['id']); ?>">Change Password</a></li>
	<li><a href="<?php echo url::site('users/logout'); ?>">Logout</a></li>
</ul>-->

This page is under construction. Sorry if it looks ugly. Profile information will go here and project information will go below. You can share this page by going to: <strong><?php echo url::base(); ?>profiles/view/<?php echo $user['id']; ?>/</strong> or <strong><?php echo url::base(); ?>profiles/view/<?php echo $user['username']; ?>/</strong>
