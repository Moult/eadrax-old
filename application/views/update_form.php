<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/image_48.png" width="48" height="48" class="icon" alt="" />
		Show people what you're working on.
	</h2>

		<?php if (isset($uid)) { ?>
		<form action="<?php echo url::base(); ?>updates/add/<?php echo $uid; ?>/" method="post" enctype="multipart/form-data">
		<?php } else { ?>
		<form action="<?php echo url::base(); ?>updates/add/" method="post" enctype="multipart/form-data">
		<?php } ?>
	<div class="form">
		<fieldset>
			<legend>
				<img src="<?php echo url::base(); ?>images/icons/clock_add.png" alt="" width="16" height="16" class="icon" />
				<?php if (isset($uid)) { ?>
				Edit Update
				<?php } else { ?>
				Summarise your project's update
				<?php } ?>
			</legend>
			<div class="elements">
				<p>
					<label for="summary" title="A short sentence describing how you've progressed and what you've done. This is the minimum requirement.">Summary<img src="<?php echo url::base(); ?>images/icons/help.png" alt="info" />:</label>
					<input type="text" id="summary" name="summary" style="height: 25px; width: 400px; font-size: 15px;" value="<?php echo $form['summary']; ?>" <?php if (isset($errors['summary'])) { echo 'class="error"'; } ?> />
				</p>
			</div>
		</fieldset>
	</div>



	<div class="form">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/clock_add.png" alt="" width="16" height="16" class="icon" />
					<?php if (isset($uid)) { ?>
					Edit Details
					<?php } else { ?>
					Add optional details
					<?php } ?>
				</legend>
				<div class="elements">
					<input type="hidden" name="did" value="<?php echo $form['did']; ?>" />
					<?php if ($this->logged_in) { ?>
					<p>
						<label for="pid">Project:
						<a href="<?php echo url::base(); ?>projects/add/"><img src="<?php echo url::base(); ?>images/icons/add.png" alt="Add" /></a></label>
						<select name="pid" id="pid">
						<?php foreach ($projects as $pid => $p_name) { ?>
						<option value="<?php echo $pid; ?>" <?php if ($form['pid'] == $pid) { echo 'selected="selected"'; } ?>><?php echo $p_name; ?></option>
						<?php } ?>
						<?php if (!empty($contributor_projects)) { ?>
						<option value="1">--Shared Projects--</option>
						<?php foreach ($contributor_projects as $pid => $p_name) { ?>
						<option value="<?php echo $pid; ?>" <?php if ($form['pid'] == $pid) { echo 'selected="selected"'; } ?>><?php echo $p_name; ?></option>
						<?php } ?>
						<?php } ?>
						</select>
					</p>
					<?php } ?>

					<p>
						<label for="detail" style="height: 20px;">Detail:</label>
						<script type="text/javascript">edToolbar('detail'); </script>
						<textarea name="detail" id="detail" cols="40" rows="6" class="resizable" <?php if (isset($errors['detail'])) { echo 'class="error"'; } ?>><?php echo $form['detail']; ?></textarea>
					</p>

					<p>
					<label for="attachment" title="<?php if ($this->logged_in) { ?>Up to 50MB per file.<?php } else { ?>Up to 5MB per file.<?php } ?> Supported filetypes include gif jpg png svg tiff bmp exr pdf zip rar tar tar.gz tar.bz ogg ogv mp3 wav avi mpg mpeg mov mp4 swf flv blend xcf doc ppt xls odt ods odp odg psd fla ai indd aep.">Attach<img src="<?php echo url::base(); ?>images/icons/help.png" alt="info" />:
						<?php if ($this->logged_in) { ?>
						<span>
<?php
if (isset($uid)) {
	$offset = 0;
	for ($i = 0; $i < 5; $i++) {
		if (!empty(${'existing_filename'. $i})) {
			$offset = $i + 1;
		}
	}
?>
							<a href="javascript:addUploadFields(1, <?php echo $offset; ?>)"><img src="<?php echo url::base(); ?>images/icons/add.png" alt="Add" id="add" /></a>
<?php } else { ?>
							<a href="javascript:addUploadFields(1, 1)"><img src="<?php echo url::base(); ?>images/icons/add.png" alt="Add" id="add" /></a>
<?php } ?>
						</span>
						<?php } ?></label>

<?php
if (isset($uid)) {
	for ($i = 0; $i < 5; $i++) {
		if (!empty(${'existing_filename'. $i})) {
?>
<div style="float: left; clear: both; margin-bottom: 5px;">
<span style="float: left; margin-right: 15px;"><img src="<?php echo ${'existing_icon'. $i}; ?>" alt="attachment<?php echo $i; ?>" style="padding: 2px; -moz-box-shadow: 1px 1px 3px #555; -webkit-box-shadow: 1px 1px 3px #555; box-shadow: 1px 1px 3px #555;" /></span>
						<span style="float: left;"><div style="overflow: hidden;"><input type="file" id="attachment" name="attachment<?php echo $i; ?>" style="height: 23px;" /></div><div style="clear: left; margin-top: 5px;"><input type="checkbox" name="delete<?php echo $i; ?>" value="1">- Delete</div></span>
</div>

<?php } } } else { ?>

						<span style="float: left;"><input type="file" id="attachment" name="attachment0" style="height: 23px;" /></span>

<?php } ?>

						<?php if ($this->logged_in) { ?>
						<span id="upload_fields_container" style="padding: 0px; margin: 0px;"></span>
						<?php } ?>
					</p>

					<p>
						<label for="syntax">Syntax:</label>
						<select id="syntax" name="syntax">
						<?php foreach ($languages as $lid => $l_name) { ?>
						<option value="<?php echo $lid; ?>" <?php if ($form['syntax'] == $lid) { echo 'selected="selected"'; } ?>><?php echo $l_name; ?></option>
						<?php } ?>
						</select>
					</p>

					<p>
						<label for="pastebin" title="Pastebinning allows other users to post revisions of your text as well as benefit from syntax highlighting.">Pastebin<img src="<?php echo url::base(); ?>images/icons/help.png" alt="info" />:</label>
						<textarea name="pastebin" id="pastebin" rows="6" cols="40" class="resizable" <?php if (isset($errors['pastebin'])) { echo 'class="error"'; } ?>><?php echo $form['pastebin']; ?></textarea>
					</p>

					<?php if (!$this->logged_in) { ?>
					<p>
						If you were logged in, we won't ask you silly questions like in primary school.
					</p>
					<p>
						<em>What is the colour of King George's favourite black horse?</em>
					</p>
					<p>
						<label for="captcha">Answer:</label>
						<input type="text" id="captcha" name="captcha" <?php if (isset($errors['captcha'])) { echo 'class="error"'; } ?> /><br /><br />
					</p>
					<?php } ?>

					<div id="overlay" style="display: none; position: fixed; text-align: center; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; -moz-opacity: 0.8; opacity: .80; filter: alpha(opacity=80); z-index: 10;">
						<div style="display: table-cell; vertical-align: middle; color: #FFF;">
							<img src="<?php echo url::base(); ?>images/loading.gif" alt="Loading" /><br />
							Please be patient as your update is submitted.<br />
							Uploading files will take a longer time to complete. Try to resist closing your browser window.
						</div>
					</div>

					<p class="submit">
						<?php if (isset($uid)) { ?>
						<input type="submit" id="submit" name="submit" class="submit" onclick="doOverlay();" value="Edit update" />
						<?php } else { ?>
						<input type="submit" id="submit" name="submit" class="submit" onclick="doOverlay();" value="Add update" />
						<?php } ?>
					</p>

<?php if (isset($uid)) { ?>
					<p>
						For whatever reason, you might wish to <a href="<?php echo url::base(); ?>updates/delete/<?php echo $uid; ?>/">delete this update</a> permanently.
					</p>
<?php } ?>
				</div>
			</fieldset>
		</form>
	</div>

</div>

<div class="right">


	<?php if (!$this->logged_in) { ?>
	<div class="form">
		<h3>
			<img src="/images/icons/warning_16.png" alt="" width="16" height="16" class="icon" />
			Oh no! You're not logged in!
		</h3>
		<div class="elements">
			<p>
			If you <a href="<?php echo url::base(); ?>users/register/">register</a> (it's <strong>free</strong>), you get:
			</p>
			<ul>
			<li>Multiple file attachments</li>
			<li>Categorise your WIPs in projects</li>
			<li>Larger upload filesizes allowed</li>
			<li>Subscribe to other projects and track users</li>
			<li>Your own personal WIPSpace</li>
			<li>Statistics on your popular WIPs</li>
			</ul>
		</div>
	</div>
	<?php } ?>


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
		<img src="<?php echo url::base(); ?>images/icons/post_note.png" alt="" />
	</div>
</div>
