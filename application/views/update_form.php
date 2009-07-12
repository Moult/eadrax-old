<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
if (isset($uid))
{
	echo form::open_multipart('updates/add/'. $uid .'/');
}
else
{
	echo form::open_multipart('updates/add/');
}
echo form::open_fieldset();
if (isset($uid))
{
	echo form::legend('Edit Update');
}
else
{
	echo form::legend('Add an Update');
}
if ($this->logged_in == TRUE)
{
	echo form::label('pid', 'Project:');
	echo form::dropdown('pid', $projects, 1) .'<br />';
}
echo form::label('summary', 'Update summary:');
echo form::input('summary', $form['summary']) .'<br />';
echo form::label('detail', 'Description:');
echo form::textarea('detail', $form['detail']) .'<br />';
echo form::label('attachment', 'File:');
echo form::upload(array('name'=>'attachment')) .'<br />';
echo form::label('syntax', 'Syntax Highlight:');
echo form::dropdown('syntax', $languages, 1) .'<br />';
echo form::label('pastebin', 'Pastebin:');
echo form::textarea('pastebin', $form['pastebin']) .'<br />';
if (isset($uid))
{
	echo form::submit('submit', 'edit update');
}
else
{
	echo form::submit('submit', 'add update');
}
echo form::close_fieldset();
echo form::close();
?>
