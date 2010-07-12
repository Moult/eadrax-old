<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Account options
	</h2>

	<div class="form">
		<form action="<?php echo url::base(); ?>profiles/options/<?php echo $this->uid; ?>/" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Edit account options.
				</legend>
				<div class="elements">
					<p>
						<label for="change_password">Change password:</label>
						<input type="checkbox" name="change_password" value="1" <?php if (isset($errors['change_password'])) { echo 'class="error"'; } ?> <?php if ($form['change_password'] == 1) { echo 'checked="checked"'; } ?> />
					</p>

					<p>
						<label for="old_password">Old password:</label>
						<input type="password" name="old_password" <?php if (isset($errors['old_password'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="new_password">New password:</label>
						<input type="password" name="new_password" <?php if (isset($errors['new_password'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="repeat_new_password">Repeat new password:</label>
						<input type="password" name="repeat_new_password" <?php if (isset($errors['repeat_new_password'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email_notifications">Email notifications:</label>
						<input type="checkbox" name="email_notifications" value="1" <?php if (isset($errors['email_notifications'])) { echo 'class="error"'; } ?> <?php if ($form['email_notifications'] == 1) { echo 'checked="checked"'; } ?> />
					</p>

					<p>
						<label for="email_public">Show email publicly:</label>
						<input type="checkbox" name="email_public" value="1" <?php if (isset($errors['email_public'])) { echo 'class="error"'; } ?> <?php if ($form['email_public'] == 1) { echo 'checked="checked"'; } ?> />
					</p>

					<p>
						<label for="allow_trackers">Allow trackers:</label>
						<input type="checkbox" name="allow_trackers" value="1" <?php if (isset($errors['allow_trackers'])) { echo 'class="error"'; } ?> <?php if ($form['allow_trackers'] == 1) { echo 'checked="checked"'; } ?> />
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Update Account Information" />
					</p>

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
		<img src="<?php echo url::base(); ?>/images/icons/user_picture.png" alt="" />
	</div>
</div>
