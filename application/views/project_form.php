<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
echo form::open('projects/add');
echo form::open_fieldset();
echo form::legend('Add a Project');
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
echo form::submit('submit', 'add project');
echo form::close_fieldset();
echo form::close();
?>
