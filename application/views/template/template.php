<?php
require 'header.php';
if (!empty($content))
{
	$i = 0;
	foreach ($content as $block)
	{
		if ($i == 0)
		{
			echo '<div id="content_top">';
			echo $block;
			if (!isset($join)) {
				echo '</div>';
			}
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
					$top_style = ' style="min-height: 65px; height: 65px;"';
				} else {
					$top_style = '';
				}

				if (!isset($join)) {
					echo '<div class="section_divider" id="section_divider'. $divider_id .'"></div>';
					echo '<div class="section_top" id="section_top'. $section_id .'"'. $top_style .'>';
					echo $block;
					echo '</div>';
				}
				$i++;
			}
			else
			{
				if ($this->uri->segment(1) == 'updates' && ($this->uri->segment(2) == 'view' || $this->uri->segment(2) == 'random') && $i == 1) { 
					echo '<div class="section_divider" id="section_divider'. $pid .'"></div>';
					echo '<div class="section_top" id="section_top'. $pid .'">';
				} elseif (!isset($join)) {
					echo '<div class="section_divider"></div>';
					echo '<div class="section_top">';
				}

				// Output the content block.
				echo $block;


				if ($this->uri->segment(1) == 'updates' && ($this->uri->segment(2) == 'view' || $this->uri->segment(2) == 'random') && $i == 1) { 
					echo '</div>';
				} elseif (!isset($join)) {
					echo '</div>';
				}
				$i++;
			}
		}
	}

	if (isset($join)) {
		echo '</div>';
	}

}
require 'footer.php';
?>
