<?=$open?>
	<p>
		<label>Name:</label>
		<?=$name?> 
		<span class="<?=$name->error_msg_class?>"><?=$name->error?></span>
	</p>
	<p>
		<label>Email:</label>
		<input type="text" name="email" value="<?=$email->value?>" class="<?=$email->class?>" onclick="<?=$email->onclick?>" />
		<span class="<?=$email->error_msg_class?>"><?=$email_error?></span>
	</p>
	<p>
		<label>Image:</label>
		<?=$image?> 
		<span class="<?=$image->error_msg_class?>"><?=$image_error?></span>
	</p>
	<p>
		<?=$submit?> 
	</p>
<?=$close?>