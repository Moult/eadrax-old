<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Join the party! WIPUP from anywhere.
	</h2>

	<p>
		WIPUP has a lovely API which follows the standardised OCS (Open Collaboration Services) REST API spec which was born along with the SocialDesktop project. The aim is to integrate web communities with desktop and mobile applications - giving you more flexibility to WIPUP your whip-ups!
	</p>

	<p>
		In a nutshell - this page gives you an API key. The OCS spec supports both API key authentication and user/pass authentication. Authentication for WIPUP's implementation supports both methods, but simply due to intuitiveness, the user/pass method is preferred. This means that we do <strong>not</strong> recommend you to use an API key, but instead just use your account.
	</p>

<?php if (empty($apikey)) { ?>
	<p>
		However, since we support it anyway, you can create one here.
	</p>
<?php } ?>

	<div class="form">
		<form action="<?php echo url::base(); ?>api/generate/" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Manage your API key
				</legend>
				<div class="elements">
					<p>
<?php if (empty($apikey)) { ?>
						<input type="checkbox" name="agree" value="1"> - Yes, I understand what I'm doing and want an API key even though the recommended auth method is user/pass.<br />
<?php } else { ?>
						<label for="apikey">API key:</label>
						<input type="text" value="<?php echo $apikey; ?>">
<?php } ?>
					</p>

<?php if (empty($apikey)) { ?>
					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Generate a key" />
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
		<img src="<?php echo url::base(); ?>/images/icons/user_picture.png" alt="" />
	</div>
</div>
