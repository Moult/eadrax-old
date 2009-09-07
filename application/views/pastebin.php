<style type="text/css"><!--
<?php echo $css; ?>
--></style>

<h2>
	<img src="<?php echo url::base(); ?>images/icons/pencil_48.png" width="48" height="48" class="icon" alt="" />
	Pastebin <?php if (!empty($syntax)) { echo '('. $syntax .')'; } ?>
</h2>

<div id="geshi" style="margin-bottom: 10px;">
	<?php echo $pastebin; ?>
</div>

<p>
<textarea style="width: 830px; height: 200px;"><?php echo $vanilla; ?></textarea>
</p>
