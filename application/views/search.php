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
		<form action="<?php echo url::base(); ?>site/search/" method="post" name="searchform">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Search
				</legend>
				<div class="elements">
					<p>
						<label for="keywords">Keywords:</label>
						<input type="text" name="keywords" value="<?php echo $form['keywords']; ?>" <?php if (isset($errors['keywords'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="search">Search for:</label>
						<select name="search" onchange="javascript:change_image(document.searchform.search.options[selectedIndex].value)">
							<option value="profiles" <?php if ($form['search'] == 'profiles') { echo 'selected="selected"'; } ?>>Profiles</option>
							<option value="projects" <?php if ($form['search'] == 'projects') { echo 'selected="selected"'; } ?>>Projects</option>
							<option value="updates" <?php if ($form['search'] == 'updates') { echo 'selected="selected"'; } ?>>Updates</option>
						</select>
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Search WIPUP" />
					</p>
				</div>
			</fieldset>
		</form>
	</div>

	<?php if (isset($results)) { ?>
	<?php foreach ($results as $result) { ?>
	<div style="clear: both; margin-bottom: 10px; padding-bottom: 5px; overflow: hidden; border-bottom: 1px dotted #AAA; position: relative;">
	<?php if ($form['search'] == 'profiles') { ?>
		<?php if (!empty($result->avatar)) { ?>
		<a href="<?php echo url::base(); ?>profiles/view/<?php echo $result->username; ?>/"><img src="<?php echo url::base(); ?>uploads/avatars/<?php echo $result->avatar; ?>_small.jpg" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" /></a>
		<?php } else { ?>
		<a href="<?php echo url::base(); ?>profiles/view/<?php echo $result->username; ?>/"><img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" /></a>
		<?php } ?>
		<span style="font-size: 16px; margin-left: 5px; font-weight: bold;"><a href="<?php echo url::base(); ?>profiles/view/<?php echo $result->username; ?>/" style="text-decoration: none;"><?php echo $result->username; ?></a></span><br />
		<?php if (!empty($result->email) && $result->email_public == 1) { ?>
		<span style="margin-left: 5px; font-size: 10px; color: #999;"><?php echo $result->email; ?><br /></span>
		<?php } ?>
		<?php if (!empty($result->description)) { ?>
		<span style="margin-left: 5px; font-size: 12px;"><?php echo substr($result->description, 0, 70); ?>...</span>
		<?php } ?>
	<?php } elseif ($form['search'] == 'projects') { ?>
		<?php if (!empty($result->icon)) { ?>
		<a href="<?php echo url::base(); ?>projects/view/<?php echo $result->uid; ?>/<?php echo $result->id; ?>/"><img src="<?php echo url::base(); ?>uploads/icons/<?php echo $result->icon; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" /></a>
		<?php } else { ?>
		<a href="<?php echo url::base(); ?>projects/view/<?php echo $result->uid; ?>/<?php echo $result->id; ?>/"><img src="<?php echo url::base(); ?>images/noprojecticon.png" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" /></a>
		<?php } ?>
		<span style="font-size: 16px; margin-left: 5px; font-weight: bold;"><a href="<?php echo url::base(); ?>projects/view/<?php echo $result->uid; ?>/<?php echo $result->id; ?>/" style="text-decoration: none;"><?php echo $result->name; ?></a></span><br />
		<?php if (!empty($result->summary)) { ?>
		<span style="margin-left: 5px; font-size: 12px;"><?php echo $result->summary; ?></span>
		<?php } ?>
	<?php } elseif ($form['search'] == 'updates') { ?>
	<?php $icon = Updates_Controller::_file_icon($result->filename0, $result->ext0); ?>
		<a href="<?php echo url::base(); ?>updates/view/<?php echo $result->id; ?>/"><img src="<?php echo $icon; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px; float: left;" /></a>
		<span style="font-size: 16px; margin-left: 5px; font-weight: bold;"><a href="<?php echo url::base(); ?>updates/view/<?php echo $result->id; ?>/" style="text-decoration: none;"><?php echo $result->summary; ?></a></span><br />
		<?php if (!empty($result->detail)) { ?>
		<span style="margin-left: 5px; font-size: 12px;"><?php echo substr($result->detail, 0, 70); ?>...</span>
		<?php } ?>
	<?php } ?>
	</div>
	<?php } if (count($results) == 0) { ?>
	Sorry, but there are no results to display for your search.
	<?php } ?>
	<?php } ?>
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
		<?php if ($form['search'] == 'profiles') { ?>
		<img src="<?php echo url::base(); ?>/images/icons/user_picture.png" alt="" id="searchimage" />
		<?php } elseif ($form['search'] == 'projects') { ?>
		<img src="<?php echo url::base(); ?>/images/icons/portfolio.png" alt="" id="searchimage" />
		<?php } elseif ($form['search'] == 'updates') { ?>
		<img src="<?php echo url::base(); ?>/images/icons/mail.png" alt="" id="searchimage" />
		<?php } ?>
	</div>
</div>
