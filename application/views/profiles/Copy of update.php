<form action="<?php echo url::site('profiles'); ?>" method="post" accept-charset="utf-8">
	<p>
		<label for="description">Description</label>
		<input type="text" description="description" value="<?php echo $user->description; ?>" id="description" />
	</p>
	<p>
		<label for="email">Email</label>
		<input type="text" email="email" value="<?php echo isset($repopulate->email) ? $repopulate->email : $user->email; ?>" id="email" />
	</p>
	<p>
		<label for="msn">MSN</label>
		<input type="text" msn="msn" value="<?php echo $user->msn; ?>" id="msn" />
	</p>
	<p>
		<label for="gtalk">Gtalk</label>
		<input type="text" gtalk="gtalk" value="<?php echo $user->gtalk; ?>" id="gtalk" />
	</p>
	<p>
		<label for="yahoo">Yahoo</label>
		<input type="text" yahoo="yahoo" value="<?php echo $user->yahoo; ?>" id="yahoo" />
	</p>
	<p>
		<label for="skype">Skype</label>
		<input type="text" skype="skype" value="<?php echo $user->skype; ?>" id="skype" />
	</p>
	<p>
		<label for="website">Website</label>
		<input type="text" website="website" value="<?php echo $user->website; ?>" id="website" />
	</p>
	<p>
		<label for="location">Location</label>
		<input type="text" location="location" value="<?php echo $user->location; ?>" id="location" />
	</p>
	<p>
		<label for="dob">DOB</label>
		<input type="text" dob="dob" value="<?php echo $user->dob; ?>" id="dob" />
	</p>
	<p>
		<label for="gender">Gender</label>
		<input type="text" gender="gender" value="<?php echo $user->gender; ?>" id="gender" />
	</p>
	<p><input type="submit" value="Update"></p>
</form>