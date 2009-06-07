<?php if (isset($msg)) { print "<div class=\"alert\">$msg</div>"; } ?>
<?php if (isset($error)) { print "<div class=\"error\">$error</div>"; } ?>
<?php if (isset($success)) { print "<div class=\"success\">$success</div>"; } ?>

<form method="post" action="try_auth">
Identity URL:
<input type="hidden" name="action" value="verify" />
<input type="text" name="openid_identifier" value="" />

<input type="submit" value="Verify" />
</form>
