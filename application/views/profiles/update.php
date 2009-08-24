<?php echo $open; ?>
	<p>
		<label for="description">Description</label>
		<?php echo $description; ?>
		<span class="<?php echo $description->error_msg_class; ?>"><?php echo $description->error; ?></span>
	</p>
	<p>
		<label for="email">Email</label>
		<?php echo $email; ?>
		<span class="<?php echo $email->error_msg_class; ?>"><?php echo $email->error; ?></span>
	</p>
	<p>
		<label for="msn">MSN</label>
		<?php echo $msn; ?>
		<span class="<?php echo $msn->error_msg_class; ?>"><?php echo $msn->error; ?></span>
	</p>
	<p>
		<label for="gtalk">Gtalk</label>
		<?php echo $gtalk; ?>
		<span class="<?php echo $gtalk->error_msg_class; ?>"><?php echo $gtalk->error; ?></span>
	</p>
	<p>
		<label for="yahoo">Yahoo</label>
		<?php echo $yahoo; ?>
		<span class="<?php echo $yahoo->error_msg_class; ?>"><?php echo $yahoo->error; ?></span>
	</p>
	<p>
		<label for="skype">Skype</label>
		<?php echo $skype; ?>
		<span class="<?php echo $skype->error_msg_class; ?>"><?php echo $skype->error; ?></span>
	</p>
	<p>
		<label for="website">Website</label>
		<?php echo $website; ?>
		<span class="<?php echo $website->error_msg_class; ?>"><?php echo $website->error; ?></span>
	</p>
	<p>
		<label for="location">Location</label>
		<?php echo $location; ?>
		<span class="<?php echo $location->error_msg_class; ?>"><?php echo $location->error; ?></span>
	</p>
	<p>
		<label for="dob">DOB</label>
		<?php echo $dob; ?>
		<span class="<?php echo $dob->error_msg_class; ?>"><?php echo $dob->error; ?></span>
	</p>
	<p>
		<label for="gender">Gender</label>
		<?php echo $gender; ?>
		<span class="<?php echo $gender->error_msg_class; ?>"><?php echo $gender->error; ?></span>
	</p>
	<p>
		<?php echo $submit; ?>
	</p>
<?php echo $close; ?>