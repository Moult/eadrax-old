<div class="left">
	<h2>
		<img src="<?php echo url::base(); ?>images/icons/comment_48.png" width="48" height="48" class="icon" alt="" />
		Comments (<?php echo $comment_total; ?>)
	</h2>

	<?php foreach ($comments as $row) { ?>
	<h3>
		<?php $comment_var_name = 'comment'. $row->uid;
		$comment_user_info = $$comment_var_name;
		if ($row->uid == 1) { echo $comment_user_info['username']; } else { echo '<a href="'. url::base() .'profile/view/'. $row->uid .'/">'. $comment_user_info['username'] .'</a>'; } ?> says:
	</h3>
	<p>
		<?php echo $row->comment; if ($this->uid == $row->uid && $this->uid != 1 || $this->uid == $update_uid && $this->uid != 1) { echo ' <a href="'. url::base() .'feedback/delete/'. $row->id .'/">[delete]</a>'; } ?>
	</p>
	<?php } ?>

	<?php if ($comment_total == 0) { ?>
	<p>
		Uhoh! It seems as though there are <strong>no comments yet</strong> for this update. Why don't you leave one?
	</p>
	<?php } ?>

	<div class="form">
		<form action="<?php echo url::base(); ?>updates/view/<?php echo $uid; ?>/" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/clock_add.png" alt="" width="16" height="16" class="icon" />
					Leave a comment
				</legend>
				<div class="elements">
					<p>
						<label for="comment">Comment:</label>
						<textarea name="comment" <?php if (isset($errors['comment'])) { echo 'class="error"'; } ?>><?php echo $form['comment']; ?></textarea>
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
						<input type="submit" name="submit" class="submit" value="Leave a comment" />
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
		<img src="<?php echo url::base(); ?>images/icons/mail.png" alt="" />
	</div>
</div>
