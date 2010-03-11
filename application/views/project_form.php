<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		<?php if (isset($pid)) { ?>
		Edit Project Information
		<?php } else { ?>
		Add a project
		<?php } ?>
	</h2>

	<p>
		Projects are a great way to categorise your works-in-progress. However if you are working on nothing in general, common with artists who do quick sketches or random personal work once in a while, you shouldn't create a project but simply put your updates in the "Uncategorised" category.
	</p>

	<p>
		Again, <strong>we're flexible</strong>. Only fill up what you need, and you can edit this anytime later.
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
						<?php foreach ($categories as $cid => $c_name) { ?>
						<option value="<?php echo $cid; ?>" <?php if (Kohana::config('projects.default_cid') == $cid) { echo 'selected="selected"'; } ?>><?php echo $c_name; ?></option>
						<?php } ?>
						</select>
					</p>

					<p>
						<label for="website">Website:</label>
						<input type="text" id="website" name="website" value="<?php echo $form['website']; ?>" <?php if (isset($errors['website'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="contributors">Contributors:</label>
						<input type="text" id="contributors" name="contributors" value="<?php echo $form['contributors']; ?>" <?php if (isset($errors['contributors'])) { echo 'class="error"'; } ?> />
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

	<div class="form">
		<h3>
			<img src="/images/icons/cup.png" alt="" width="16" height="16" class="icon" />
			Some tips
		</h3>
		<div class="elements">
			<h4>
				The Purpose of projects
			</h4>
			<p>
				The project system is not designed for project <em>management</em>, it is not a substitute for version control or other collaborative solutions but instead a lightweight system to be used <em>alongside</em> these.
			</p>
			
			<h4>
				Contributors
			</h4>
			<p>
				When you list contributors for your project, you can seperate names with commas and put username aliases in brackets. If either of these matches a username in our database we'll turn it into a link to their profile.
			</p>

			<h4>
				Featured Projects
			</h4>
			<p>
				We'll trawl the site for interesting projects and feature the ones we like every so often on the front page. If you don't want us to consider your project, just make a note in your project description.
			</p>
		</div>
	</div>

	<div id="picture">
		<img src="<?php echo url::base(); ?>/images/icons/portfolio.png" alt="" />
	</div>
</div>
