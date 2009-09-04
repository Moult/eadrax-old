<h2>
	<img src="<?php echo url::base(); ?>images/icons/pencil_48.png" width="48" height="48" class="icon" alt="" />
	Pastebin <?php if (!empty($syntax)) { echo '('. $syntax .')'; } ?>
</h2>

<div id="geshi">
	<?php echo $pastebin; ?>
</div>

<p>
<textarea style="width: 100%; height: 200px;"><?php echo $vanilla; ?></textarea>
</p>
