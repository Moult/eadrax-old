<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Remove contributor
	</h2>

	<p>
		Removing yourself as a contributor is permanent. It may be useful to delete contributed projects that are no longer being actively worked on. Any updates you have contributed to this project will still exist, but will no longer show up in your WIPSpace's project list.
	</p>

	<div class="form">
		<form action="<?php echo url::base() .'projects/nocontribute/'. $pid; ?>" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Delete contributor
				</legend>
				<div class="elements">
					<p>
						Yes, I want to delete myself as a contributor.
					</p>
					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Delete Contributor" />
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
