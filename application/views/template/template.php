<?php
require 'header.php';
if (!empty($content))
{
	$i = 0;
	foreach ($content as $block)
	{
		if ($i == 0)
		{
			echo '<div id="content_top_left"></div>';
			echo '<div id="content_top">';
			echo $block;
			echo '</div>';
			echo '<div id="content_top_right"></div>';
			$i++;
		}
		elseif ($i == 1)
		{
			echo '<div class="section_top_left"></div>';
			echo '<div class="section_top">';
			echo $block;
			echo '</div>';
			echo '<div class="section_top_right"></div>';
		}
	}
}
require 'footer.php';
?>
