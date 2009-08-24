<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php echo $head; ?>
	</head>
	<body>
		
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
	</body>
</html>