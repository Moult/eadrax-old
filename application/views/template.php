<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript" src="<?php echo url::base(); ?>js/prototype.js"></script>
<script type="text/javascript" src="<?php echo url::base(); ?>js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="<?php echo url::base(); ?>js/lightbox.js"></script>
<link rel="stylesheet" href="<?php echo url::base(); ?>css/lightbox.css" type="text/css" media="screen" />

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
