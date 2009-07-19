<form action="<?php echo url::site('profiles/update/' . $user->id); ?>" method="post" accept-charset="utf-8">
	<p>
		<label for="description">Description</label>
		<input type="text" name="description" value="<?php echo $user->description; ?>" id="description" />
	</p>
	<p>
		<label for="email">Email</label>
		<input type="text" name="email" value="<?php echo $repopulate->email ? $repopulate->email : $user->email; ?>" id="email" />
	</p>
	<p>
		<label for="msn">MSN</label>
		<input type="text" name="msn" value="<?php echo isset($repopulate->msn) ? $repopulate->msn : $user->msn; ?>" id="msn" />
	</p>
	<p>
		<label for="gtalk">Gtalk</label>
		<input type="text" name="gtalk" value="<?php echo isset($repopulate->gtalk) ? $repopulate->gtalk : $user->gtalk; ?>" id="gtalk" />
	</p>
	<p>
		<label for="yahoo">Yahoo</label>
		<input type="text" name="yahoo" value="<?php echo isset($repopulate->yahoo) ? $repopulate->yahoo : $user->yahoo; ?>" id="yahoo" />
	</p>
	<p>
		<label for="skype">Skype</label>
		<input type="text" name="skype" value="<?php echo isset($repopulate->skype) ? $repopulate->skype : $user->skype; ?>" id="skype" />
	</p>
	<p>
		<label for="website">Website</label>
		<input type="text" name="website" value="<?php echo $repopulate->website ? $repopulate->website : $user->website; ?>" id="website" />
	</p>
	<p>
		<label for="location">Location</label>
		<input type="text" name="location" value="<?php echo isset($repopulate->location) ? $repopulate->location : $user->location; ?>" id="location" />
	</p>
	<p>
		<label for="dob">DOB</label>
		<input type="text" name="dob" value="<?php echo isset($repopulate->dob) ? $repopulate->dob : $user->dob; ?>" id="dob" />
	</p>
	<p>
		<label for="gender">Gender</label>
		<select name="gender" id="gender">
			<?php foreach(Kohana::config('profiles.gender') as $key => $gender){ ?>
			<option value="<?php echo $key; ?>" <?php if(!(strcmp($key, (isset($repopulate->gender)) ? $repopulate->gender : $user->gender))){echo "selected=\"selected\"";} ?>><?php echo $gender; ?></option>
			<?php } ?>
		</select>
	</p>
	<p><input type="submit" value="Update"></p>
</form>