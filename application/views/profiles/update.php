<?php echo $open; ?>
	<p>
		<label for="description">Description</label>
		<?php echo $description; ?>
		<span class="<?php echo $description->error_msg_class; ?>"><?php echo $description->error; ?></span>
	</p>
	<p>
		<label for="email">Email</label>
		<?php echo $email; ?>
		<span class="<?php echo $email->error_msg_class; ?>"><?php echo $email->error; ?></span>
	</p>
	<p>
		<label for="msn">MSN</label>
		<?php echo $msn; ?>
		<span class="<?php echo $msn->error_msg_class; ?>"><?php echo $msn->error; ?></span>
	</p>
	<p>
		<label for="gtalk">Gtalk</label>
		<?php echo $gtalk; ?>
		<span class="<?php echo $gtalk->error_msg_class; ?>"><?php echo $gtalk->error; ?></span>
	</p>
	<p>
		<label for="yahoo">Yahoo</label>
		<?php echo $yahoo; ?>
		<span class="<?php echo $yahoo->error_msg_class; ?>"><?php echo $yahoo->error; ?></span>
	</p>
	<p>
		<label for="skype">Skype</label>
		<?php echo $skype; ?>
		<span class="<?php echo $skype->error_msg_class; ?>"><?php echo $skype->error; ?></span>
	</p>
	<p>
		<label for="website">Website</label>
		<?php echo $website; ?>
		<span class="<?php echo $website->error_msg_class; ?>"><?php echo $website->error; ?></span>
	</p>
	<p>
		<label for="location">Location</label>
		<?php echo $location; ?>
		<span class="<?php echo $location->error_msg_class; ?>"><?php echo $location->error; ?></span>
	</p>
	<p>
		<label for="dob">DOB</label>
		<?php echo $dob; ?>
		<span class="<?php echo $dob->error_msg_class; ?>"><?php echo $dob->error; ?></span>
	</p>
	<p>
		<label for="gender">Gender</label>
		<?php echo $gender; ?>
		<span class="<?php echo $gender->error_msg_class; ?>"><?php echo $gender->error; ?></span>
	</p>
	<p>
		<?php echo $submit; ?>
	</p>
<?php echo $close; ?>

<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Tell us about yourself.
	</h2>

	<p>
		Others might want to find out a bit more about you as they check out your work, so give them that chance and don't be selfish.
	</p>

	<div class="form">
		<form action="<?php echo url::base(); ?>users/register/" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Update your profile.
				</legend>
				<div class="elements">
					<p>
						<label for="openid_identifier">Username:</label>
						<input type="text" name="openid_identifier" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['openid_identifier'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email">Gender:</label>
						<select name="gender" id="gender">
							<option value="Unknown" selected="selected">Confused</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>	
					</p>

					<p>
						<label for="email">Email:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="pastebin">About you:</label>
						<textarea name="pastebin" <?php if (isset($errors['pastebin'])) { echo 'class="error"'; } ?>><?php //echo $form['pastebin']; ?></textarea>
					</p>

					<p>
						<label for="email">DOB (d/m/y):</label>
						<select name="gender" id="gender">
							<option value="Unknown" selected="selected">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
						</select>
						/
						<select name="gender" id="gender">
							<option value="Unknown" selected="selected">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
						</select>
						/
						<select name="gender" id="gender">
							<option value="Unknown" selected="selected">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
						</select>
					</p>

					<p>
						<label for="email">MSN:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email">Jabber/GTalk:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email">Yahoo:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email">Skype:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email">Website:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="email">Location:</label>
						<input type="text" name="email" value="<?php //echo $form['openid_identifier']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Update Profile" />
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
