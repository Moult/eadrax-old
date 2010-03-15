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
		elseif ($i > 0)
		{
			if (!empty($pids)) 
			{
				if ($i == 1) {
					$divider_id = '';
					$section_id = $pids[$i-1];
				} else {
					$divider_id = $pids[$i-2];
					if (!empty($pids[$i-1])) {
						$section_id = $pids[$i-1];
					} else {
						$section_id = '';
					}
				}
				if ($section_id != '') {
					// We need specific styles to be added to the defaults.
					$side_style = ' style="height: 100px;"';
					$top_style = ' style="min-height: 90px;"';
				} else {
					$side_style = '';
					$top_style = '';
				}

				if ($divider_id != '') {
					$divider_style = ' style="background-color: #E3F8FF; height: 18px;"';
				} else {
					$divider_style = '';
				}


				echo '<div class="section_divider" id="section_divider'. $divider_id .'"'. $divider_style .'></div>';
				echo '<div class="section_top_left" id="section_top_left'. $section_id .'"'. $side_style .'></div>';
				echo '<div class="section_top" id="section_top'. $section_id .'"'. $top_style .'>';
				echo $block;
				echo '</div>';
				echo '<div class="section_top_right" id="section_top_right'. $section_id .'"'. $side_style .'></div>';
				$i++;
			}
			else
			{
				echo '<div class="section_divider"></div>';
				echo '<div class="section_top_left"></div>';
				echo '<div class="section_top">';
				echo $block;
				echo '</div>';
				echo '<div class="section_top_right"></div>';
			}
		}
	}
}
require 'footer.php';
?>
