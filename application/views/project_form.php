<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
if (isset($pid))
{
	echo form::open_multipart('projects/add/'. $pid .'/');
}
else
{
	echo form::open_multipart('projects/add/');
}
echo form::open_fieldset();
if (isset($pid))
{
	echo form::legend('Edit Project');
}
else
{
	echo form::legend('Add a Project');
}
echo form::label('name', 'Project Name:');
echo form::input('name', $form['name']) .'<br />';
echo form::label('cid', 'Category:');
echo form::dropdown('cid', $categories, Kohana::config('projects.default_cid')) .'<br />';
echo form::label('website', 'Website:');
echo form::input('website', $form['website']) .'<br />';
echo form::label('contributors', 'Contributors (separate with comma):');
echo form::input('contributors', $form['contributors']) .'<br />';
echo form::label('description', 'Description:');
echo form::textarea('description', $form['description']) .'<br />';
echo form::label('icon', 'Project Icon:');
echo form::upload(array('name'=>'icon')) .'<br />';
if (isset($pid))
{
	echo form::submit('submit', 'edit project');
}
else
{
	echo form::submit('submit', 'add project');
}
echo form::close_fieldset();
echo form::close();
?>
