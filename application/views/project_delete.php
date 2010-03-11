<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Delete project
	</h2>

	<p>
		This deletion is permanent. It may be useful to delete projects that are no longer being actively worked on and archive them within your uncategorised category.
	</p>

	<div class="form">
		<form action="<?php echo url::base() .'projects/delete/'. $pid; ?>" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Delete project
				</legend>
				<div class="elements">
					<p>
						<label for="new_pid">Move updates to:</label>
						<select name="new_pid" id="new_pid">
						<option value="0" selected="selected">Delete updates</option>
						<?php foreach ($projects as $pid => $p_name) { ?>
						<option value="<?php echo $pid; ?>"><?php echo $p_name; ?></option>
						<?php } ?>
						</select>
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Delete Project" />
					</p>
				</div>
			</fieldset>
		</form>
	</div>

</div>

<div class="right">
	<div id="picture">
		<img src="<?php echo url::base(); ?>/images/icons/portfolio.png" alt="" />
	</div>
</div>
