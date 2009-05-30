<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="box">
	<p>This is the default Kohana index page. You may also access this page as <code><?php echo html::anchor('welcome/index', 'welcome/index') ?></code>.</p>

	<p>
		To change what gets displayed for this page, edit <code>application/controllers/welcome.php</code>.<br />
		To change this text, edit <code>application/views/welcome_content.php</code>.
	</p>
</div>

<!-- Temporary test forms -->

<form action="http://localhost/eadrax/users/register/" method="post">
<input type="text" name="username"><input type="text" name="password">
<input type="submit" value="register">
</form>

<form action="http://localhost/eadrax/users/login/" method="post">
<input type="text" name="username"><input type="text" name="password">
<input type="submit" value="login">
</form>

<?php
if (!empty($content))
{
	echo $content;
}
?>
