<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Get ready to join WIPUP.
	</h2>

	<p>
		Using WIPUP requires nothing more than a username and a password. You can add more information to your account later.
	</p>

	<p>
		With a WIPUP account, you gain benefits to member-only features such as multiple file uploads, project categorisations, notifications, statistics and more!
	</p>

	<div class="form">
		<form action="<?php echo url::base(); ?>users/register/" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>/images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Create an account
				</legend>
				<div class="elements">
					<p>
						<label for="openid_identifier">Username:</label>
						<input type="text" name="openid_identifier" value="<?php echo $form['openid_identifier']; ?>" <?php if (isset($errors['openid_identifier'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="password">Password:</label>
						<input type="password" name="password" <?php if (isset($errors['password'])) { echo 'class="error"'; } ?> />
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Register Account" />
					</p>

					<p>
						By using this site, you agree to our <a href="<?php echo url::base() .'site/legal/'; ?>">legal and licensing information</a>.
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
		<fieldset>
			<legend>
				<img src="/images/icons/warning_16.png" alt="" width="16" height="16" class="icon" />
				Errors Occured
			</legend>
			<div class="elements">
				<ol class="errors">
				<?php
				foreach ($errors as $error) {
					echo '<li>'. $error .'</li><br />';
				}
				?>
				</ol>
			</div>
		</fieldset>
	</div>
	<?php } ?>

	<div id="picture">
		<img src="/images/user_picture.png" alt="" />
	</div>
</div>
