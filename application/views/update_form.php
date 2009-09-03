<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/image_48.png" width="48" height="48" class="icon" alt="" />
		Show people what you're working on.
	</h2>

	<p>
		Here is where you can update the progress on a project. Your updates will be public and shown right away. You can then view and share your chronological progress via your profile or project page.
	</p>

	<p>
		<strong>We're flexible.</strong> You don't need to fill up everything in the form below - only what you need. We only ask for a summary.
	</p>

	<?php if (!$this->logged_in) { ?>
	<p>
		If seems as though you are <strong>not logged in</strong>. If you <a href="<?php echo url::base(); ?>users/register/">register</a>, you will receive benefits such as project statistics, timelines, permanent file storage, and so many cool things it's not funny any more.
	</p>
	<?php } ?>

	<div class="form">
		<?php if (isset($uid)) { ?>
		<form action="<?php echo url::base(); ?>updates/add/<?php echo $uid; ?>/" method="post">
		<?php } else { ?>
		<form action="<?php echo url::base(); ?>updates/add/" method="post">
		<?php } ?>
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/clock_add.png" alt="" width="16" height="16" class="icon" />
					<?php if (isset($uid)) { ?>
					Edit Update
					<?php } else { ?>
					Add an update
					<?php } ?>
				</legend>
				<div class="elements">
					<?php if ($this->logged_in) { ?>
					<p>
						<label for"pid">Project:</label>
						<select name="pid">
						<?php foreach ($projects as $pid => $p_name) { ?>
						<option value="<?php echo $pid; ?>"><?php echo $p_name; ?></option>
						<?php } ?>
						</select>
					</p>
					<?php } ?>

					<p>
						<label for="summary">Summary:</label>
						<input type="text" name="summary" value="<?php echo $form['summary']; ?>" <?php if (isset($errors['summary'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="detail">Detail:</label>
						<textarea name="detail" <?php if (isset($errors['detail'])) { echo 'class="error"'; } ?>><?php echo $form['detail']; ?></textarea>
					</p>

					<p>
						<label for="attachment">Attach:</label>
						<input type="file" name="attachment" />
					</p>

					<p>
						<label for"syntax">Syntax:</label>
						<select name="syntax">
						<?php foreach ($languages as $lid => $l_name) { ?>
						<option value="<?php echo $lid; ?>"><?php echo $l_name; ?></option>
						<?php } ?>
						</select>
					</p>

					<p>
						<label for="pastebin">Pastebin:</label>
						<textarea name="pastebin" <?php if (isset($errors['pastebin'])) { echo 'class="error"'; } ?>><?php echo $form['pastebin']; ?></textarea>
					</p>

					<?php if (!$this->logged_in) { ?>
					<p>
						If you were logged in, we won't ask you silly questions like in primary school.
					</p>
					<p>
						<label for="captcha">Eye test:</label>
						<input type="text" name="captcha" <?php if (isset($errors['captcha'])) { echo 'class="error"'; } ?> /><br /><br />
					</p>
					<p>
						<img src="<?php echo url::base(); ?>image/securimage/" alt="captcha" />
					</p>
					<?php } ?>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Add status update" />
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
			<?php if (!$this->logged_in) { ?>
			<h4>
				Anonymous File Limitations
			</h4>
			<p>
				As a guest, your files are stored temporarily and will be removed periodically. You are also limited to a maximum filesize of 5MB.
			</p>
			<?php } ?>
			
			<h4>
				File Support
			</h4>
			<p>
				gif jpg png svg tiff bmp exr pdf zip rar tar tar.gz tar.bz ogg mp3 wav avi mpg mov swf flv blend xcf doc ppt xls odt ods odp odg psd fla ai indd aep
			</p>

			<h4>
				The Pastebin
			</h4>
			<p>
				Sometimes you would like to paste source code snippets online. We do not allow single source code file uploads here, such as a .php file, but we do allow you to paste the code. If you want to release a lot of source code, consider compressing it into a single file and uploading it as a package.
			</p>
		</div>
	</div>

	<div id="picture">
		<img src="<?php echo url::base(); ?>images/icons/post_note.png" alt="" />
	</div>
</div>
