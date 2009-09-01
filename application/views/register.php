<div class="left">
	<h2>
		<img src="/images/icons/circle_green.png" width="48" height="48" class="icon" alt="" />
		Get ready to join WIPUP.
	</h2>

	<p>
		Using WIPUP requires nothing more than a username and a password. You can add more information to your account later.
	</p>

	<p>
		With a WIPUP account, you gain benefits to member-only features such as multiple file uploads, project categorisations, notifications, statistics and more!
	</p>

	<div class="form">
		<form action="<?php echo url::base(); ?>users/register/" method="post">
			<fieldset>
				<legend>
					<img src="/images/icons/form.png" alt="" width="16" height="16" class="icon" />
					Create an account
				</legend>
				<div class="elements">
					<p>
						<?php
						echo form::label('username', 'Username:');
						echo form::input('openid_identifier', $form['openid_identifier']);
						?>
					</p>

					<p>
						<?php
						echo form::label('password', 'Password:');
						echo form::password('password');
						?>
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Login or Register" />
					</p>

					<p style="text-align: center;">
						By using this site, you agree to our <a href="<?php echo url::base() .'site/legal/'; ?>">legal and licensing information</a>.
					</p>
				</div>
			</fieldset>
		</form>
	</div>



</div>

<div class="right">
	<div class="form">
		<fieldset>
			<legend>
				<img src="/images/icons/warning_16.png" alt="" width="16" height="16" class="icon" />
				Errors Occured
			</legend>
			<div class="elements">
				<p>
					asdf
				</p>
			</div>
		</fieldset>
	</div>

	<div id="picture">
		<img src="/images/user_picture.png" alt="" />
	</div>
</div>



<?php
if (isset($errors))
{
	foreach ($errors as $error)
	{
		echo $error .'<br />';
	}
}
/**
echo form::open('users/register');
echo form::open_fieldset();
echo form::legend('Register an account');
echo form::label('username', 'Username:');
echo form::input('openid_identifier', $form['openid_identifier']);
echo form::label('password', 'Password:');
echo form::password('password');
echo form::submit('submit', 'register');
echo form::close_fieldset();
echo form::close();
 */
?>
