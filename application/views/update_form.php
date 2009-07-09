<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
echo form::open_multipart('updates');
echo form::open_fieldset();
echo form::legend('Add an Update');
echo form::label('pid', 'Project:');
echo form::dropdown('pid', $projects, 1) .'<br />';
echo form::label('summary', 'Update summary:');
echo form::input('summary', $form['summary']) .'<br />';
echo form::label('detail', 'Description:');
echo form::textarea('detail', $form['detail']) .'<br />';
echo form::submit('submit', 'add update');
echo form::close_fieldset();
echo form::close();
?>
