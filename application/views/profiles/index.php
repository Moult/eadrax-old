<h2>Welcome <?php echo $user->username; ?></h2>
<ul>
	<li><a href="<?php echo url::site('profiles/update/' . $user->id); ?>">Update Information</a></li>
</ul>