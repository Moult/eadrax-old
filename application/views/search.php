<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Find a project, update, or profile.
	</h2>

	<p>
		Searching WIPUP defaults to a boolean AND operator. This means that search results will contain each and all of your keywords, in any order. Separate keywords by a space.
	</p>

	<script type="text/javascript">
		function change_image(search) {
			if (search == "projects") {
				document.searchimage.src = "<?php echo url::base(); ?>images/icons/portfolio.png";
			} else if (search == "updates") {
				document.searchimage.src = "<?php echo url::base(); ?>images/icons/mail.png";
			} else {
				document.searchimage.src = "<?php echo url::base(); ?>images/icons/user_picture.png";
			}
		}
	</script>

	<div class="form">
		<form action="<?php echo url::base(); ?>profiles/options/<?php echo $this->uid; ?>/" method="post" name="searchform">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Search
				</legend>
				<div class="elements">
					<p>
						<label for="keywords">Keywords:</label>
						<input type="text" name="keywords" <?php //if (isset($errors['old_password'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="search">Search for:</label>
						<select name="search" onchange="javascript:change_image(document.searchform.search.options[selectedIndex].value)">
							<option value="profiles">Profiles</option>
							<option value="projects">Projects</option>
							<option value="updates">Updates</option>
						</select>
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Search WIPUP" />
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
		<img src="<?php echo url::base(); ?>/images/icons/user_picture.png" alt="" id="searchimage" />
	</div>
</div>
