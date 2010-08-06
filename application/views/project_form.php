<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/app_48.png" width="48" height="48" class="icon" alt="" />
		<?php if (isset($pid)) { ?>
		Edit Project Information
		<?php } else { ?>
		Add a project
		<?php } ?>
	</h2>

	<p>
		Projects are a great way to categorise your long-term works-in-progress. Short-term projects are normally best left in the Uncategorised project. Note that WIPUP is not a project management tool.
	</p>

	<div class="form">
		<?php
		if (isset($pid))
		{
			echo '<form action="'. url::base() .'projects/add/'. $pid .'" method="post" enctype="multipart/form-data">';
		}
		else
		{
			echo '<form action="'. url::base() .'projects/add/" method="post" enctype="multipart/form-data">';
		}
		?>
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					<?php if (isset($pid)) { ?>
					Edit Project Information
					<?php } else { ?>
					Add a new project
					<?php } ?>
				</legend>
				<div class="elements">
					<p>
						<label for="name">Name:</label>
						<input type="text" id="name" name="name" value="<?php echo $form['name']; ?>" <?php if (isset($errors['name'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="cid">Category:</label>
						<select name="cid" id="cid">
						<?php $select = TRUE; foreach ($categories as $cid => $c_name) { ?>
						<option value="<?php echo $cid; ?>" <?php if ($form['cid'] == $cid) { echo 'selected="selected"'; $select = FALSE; } if (Kohana::config('projects.default_cid') == $cid && $select == TRUE) { echo 'selected="selected"'; } ?>><?php echo $c_name; ?></option>
						<?php } ?>
						</select>
					</p>

					<p>
						<label for="website">Website:</label>
						<input type="text" id="website" name="website" value="<?php echo $form['website']; ?>" <?php if (isset($errors['website'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="contributors" title="When you list contributors for your project, if the username in brackets matches a user on WIPUP, we'll give them access to add updates to the project as well.">Contributors<img src="<?php echo url::base(); ?>images/icons/help.png" alt="info" />:</label>
						<input type="text" id="contributors" name="contributors" value="<?php echo $form['contributors']; ?>" <?php if (isset($errors['contributors'])) { echo 'class="error"'; } ?> />
						<br /><br />Format: John Doe (john_username), Jane Doe, James Doe, (another_username)
					</p>

					<p>
						<label for="summary">Summary:</label>
						<input type="text" id="summary" style="width: 400px;" name="summary" value="<?php echo $form['summary']; ?>" <?php if (isset($errors['summary'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="description" style="height: 20px;">Description:</label>
						<script type="text/javascript">edToolbar('detail'); </script>
						<textarea name="description" id="detail" class="resizable" <?php if (isset($errors['description'])) { echo 'class="error"'; } ?>><?php echo $form['description']; ?></textarea>
					</p>

					<p>
						<label for="icon">Icon:</label>
						<input type="file" id="icon" name="icon" />
					</p>

					<p class="submit">
						<?php if (isset($pid)) { ?>
						<input type="submit" name="submit" class="submit" value="Edit Project" />
						<?php } else { ?>
						<input type="submit" name="submit" class="submit" value="Add New Project" />
						<?php } ?>
					</p>

					<?php if (isset($pid)) { ?>
					<p>
						If you no longer work on this project you may wish to <a href="<?php echo url::base(); ?>projects/delete/<?php echo $pid; ?>/">delete this project</a>.
					</p>
					<?php } ?>
				</div>
			</fieldset>
		</form>
	</div>

</div>

<div class="right">

	<?php
	if (isset($errors)) {
	?>
	<div class="form">
		<h3>
			<img src="/images/icons/warning_16.png" alt="" width="16" height="16" class="icon" />
			Errors Occured
		</h3>
		<div class="elements">
			<ol class="errors">
			<?php
			foreach ($errors as $error) {
				echo '<li>'. $error .'</li><br />';
			}
			?>
			</ol>
		</div>
	</div>
	<?php } ?>

	<div id="picture">
		<img src="<?php echo url::base(); ?>/images/icons/portfolio.png" alt="" />
	</div>
</div>
