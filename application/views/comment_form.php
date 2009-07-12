<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
echo form::open_multipart('updates/comment/'. $uid .'/');
echo form::open_fieldset();
echo form::legend('Add a Comment');
echo form::label('comment', 'Comment:');
echo form::textarea('comment', $form['comment']) .'<br />';
echo form::submit('submit', 'add comment');
echo form::close_fieldset();
echo form::close();
?>
