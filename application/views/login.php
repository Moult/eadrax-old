<?php
if (isset($error))
{
	echo $error .'<br />';
}

echo form::open('users/login');
echo form::open_fieldset();
echo form::legend('Log in');
echo form::label('username', 'Username:');
echo form::input('username');
echo form::label('password', 'Password:');
echo form::password('password');
echo form::submit('submit', 'login');
echo form::close_fieldset();
echo form::close();
?>
