<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
Header
<br /><br />
<?php
if (!empty($content))
{
	foreach ($content as $content)
	{
		echo '[[<br />';
		echo $content;
		echo '<br />]]<br />';
	}
}
?>
<br /><br />
Footer
