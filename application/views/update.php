Viewing update <?php echo $id; ?><br />
Update summary: <?php echo $summary; ?><br />
<?php
if ($display == 'image')
{
	if (file_exists(DOCROOT .'uploads/files/'. $filename .'_fit.jpg'))
	{
		echo '<a href="'. url::base() .'uploads/files/'. $filename .'.'. $ext .'" rel="lightbox" title="'. $summary .'"><img src="'. url::base() .'uploads/files/'. $filename .'_fit.jpg"></a>';
	}
	else
	{
		echo '<a href="'. url::base() .'uploads/files/'. $filename .'.'. $ext .'" rel="lightbox" title="'. $summary .'"><img src="'. url::base() .'uploads/files/'. $filename .'.'. $ext .'"></a>';
	}
}
