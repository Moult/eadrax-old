<?php echo $open; ?>
	<p>
		<label for="password">Password</label>
		<?php echo $password; ?>
		<span class="<?php echo $password->error_msg_class; ?>"><?php echo $password->error; ?></span>
	</p>
	<p>
		<label for="password2">Confirm Password</label>
		<?php echo $password2; ?>
		<span class="<?php echo $password2->error_msg_class; ?>"><?php echo $password2->error; ?></span>
	</p>
	<p>
		<?php echo $submit; ?>
	</p>
<?php echo $close; ?>