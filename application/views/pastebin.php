<style type="text/css"><!--
<?php echo $css; ?>
--></style>

<?php if (isset($revisions)) { ?>
<div style="display: none;"><div id="revisions"><?php echo $revisions; ?></div></div>
<?php } ?>

<h2>
	<img src="<?php echo url::base(); ?>images/icons/pencil_48.png" width="48" height="48" class="icon" alt="" />
	Pastebin <?php if (!empty($syntax)) { echo '('. $syntax .')'; } ?>
</h2>

<div id="geshi" style="margin-bottom: 10px;">
	<?php echo $pastebin; ?>
</div>

<div class="form">
	<form action="<?php echo url::base(); ?>updates/add/" method="post" name="revision">
		<fieldset>
			<legend>
				<img src="<?php echo url::base(); ?>images/icons/clock_add.png" alt="" width="16" height="16" class="icon" />
				Revise Paste
			</legend>
			<div class="elements">
				<textarea style="width: 810px; height: 200px;" name="pastebin" id="pastebin" class="resizable"><?php echo $vanilla; ?></textarea>
				<input type="hidden" name="did" value="<?php echo $id; ?>">
				<input type="submit" name="submit" style="height: 19px; width: 100px; margin-right: 6px;" value="Add revision">
				<input type="button" name="clear" style="height: 19px; width: 100px; margin-right: 6px;" value="Clear changes" onclick="document.revision.reset(); return false;">
				<input type="text" name="summary" id="summary" style="height: 17px; width: 300px; color: #999;" value="Describe your changes" onclick="if(document.getElementById('summary').value == 'Describe your changes') { document.getElementById('summary').value=''; document.getElementById('summary').style.color = '#000'; }">
<?php if ($update_revisions > 1) { ?>
				<span style="float: right;">
					<a id="inline" href="#revisions"><input type="button" name="compare" style="height: 19px; width: 150px;" value="Compare diffs (<?php echo $update_revisions - 1; ?>)"></a>
				</span>
<?php } ?>
				<div style="clear: both;"></div>
			</div>
		</fieldset>
	</form>
</div>
