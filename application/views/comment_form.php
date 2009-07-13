<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
echo form::open_multipart('feedback/add/'. $uid .'/');
echo form::open_fieldset();
echo form::legend('Add a Comment');
echo form::label('comment', 'Comment:');
echo form::textarea('comment', $form['comment']) .'<br />';
if ($this->logged_in == FALSE)
{
	echo form::label('captcha', 'CAPTCHA:');
	echo form::input('captcha', '', 'maxlength="6"') .'<br />';
	echo html::image('image/securimage', NULL, TRUE);
}
echo form::submit('submit', 'add comment');
echo form::close_fieldset();
echo form::close();
?>
