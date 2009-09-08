function login_username()
{
	if (document.login_form.openid_identifier.value == "Username")
	{
		document.login_form.openid_identifier.value = "";
		document.login_form.openid_identifier.style.color = '#000000';
	}
}

function login_password()
{
	document.login_form.password.value = "";
	document.login_form.password.style.color = '#000000';
}

function quickup_summary()
{
	if (document.quickup.upload_description.value == "The QuickUp doesn't work at the moment. Sorry!")
	{
		document.quickup.upload_description.value = "";
		document.quickup.upload_description.style.color = '#000000';
	}

}
