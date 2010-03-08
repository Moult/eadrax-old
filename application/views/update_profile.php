<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Tell us about yourself.
	</h2>

	<p>
		Others might want to find out a bit more about you as they check out your work, so give them that chance and don't be selfish.
	</p>

	<div class="form">
	<form action="<?php echo url::base(); ?>profiles/update/<?php echo $this->uid; ?>" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Update your profile.
				</legend>
				<div class="elements">
					<p>
						<label for="gender">Gender:</label>
						<select name="gender" id="gender">
							<option value="Confused" selected="selected">Confused</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>	
					</p>

					<p>
						<label for="email">Email:</label>
						<input type="text" id="email" name="email" value="<?php echo $form['email']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="description">About you:</label>
						<textarea id="description" name="description" <?php if (isset($errors['pastebin'])) { echo 'class="error"'; } ?>><?php echo $form['description']; ?></textarea>
					</p>

					<p>
						<label for="dd">DOB (d/m/y):</label>
						<select name="dd" id="dd">
							<option value="1" selected="selected">1</option>
							<?php for($i=2;$i<32;$i++) { ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
						/
						<select name="mm" id="mm">
							<option value="1" selected="selected">1</option>
							<?php for($i=2;$i<13;$i++) { ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
						/
						<select name="yyyy" id="yyyy">
							<option value="2003" selected="selected">2003</option>
							<?php for($i=2002;$i>1932;$i--) { ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
					</p>

					<p>
						<label for="msn">MSN:</label>
						<input type="text" id="msn" name="msn" value="<?php echo $form['msn']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="gtalk">Jabber/GTalk:</label>
						<input type="text" id="gtalk" name="gtalk" value="<?php echo $form['gtalk']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="yahoo">Yahoo:</label>
						<input type="text" id="yahoo" name="yahoo" value="<?php echo $form['yahoo']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="skype">Skype:</label>
						<input type="text" id="skype" name="skype" value="<?php echo $form['skype']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="website">Website:</label>
						<input type="text" id="website" name="website" value="<?php echo $form['website']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="location">Location:</label>
						<input type="text" id="location" name="location" value="<?php echo $form['location']; ?>" <?php if (isset($errors['email'])) { echo 'class="error"'; } ?> />
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
