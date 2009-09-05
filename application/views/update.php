<h2>
	<img src="<?php echo url::base(); ?>images/icons/spanner_48.png" width="48" height="48" class="icon" alt="" />
	<?php echo $summary; ?>
</h2>

<div style="float: left;font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;">
	<?php if ($uid != 1) { ?>
	Viewing Project: <em><?php if ($pid != 1) { ?><a href="<?php echo url::base(); ?>projects/view/<?php echo $pid; ?>"><?php echo $project_information['name']; ?></a><?php } else { ?>Uncategorised<?php } ?></em>
	<?php } ?>
	by <em><?php if ($uid != 1) { ?><a href="<?php echo url::base(); ?>profile/view/<?php echo $uid; ?>/"><?php echo $user_information['username']; ?></a><?php } else { ?>Guest<?php } ?> with <?php echo $kudos; ?> kudos</em>
</div>

<div style="float: right; font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;"><?php echo $logtime; ?></div>

<div style="clear: both;"></div>


<div style="float: left">

    <ul style="margin-left: 0px; display: inline;">
        <li style="width: 70px; display: inline;">
			<input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base(); ?>feedback/kudos/<?php echo $id; ?>/'" value="Kudos">
        </li>
<?php if ($uid != 1) { ?>
	<?php if ($pid != 1) { ?>
        <li style="width: 70px; display: inline;">
			<?php if ($subscribed == TRUE) { ?>
            <input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base(); ?>feedback/unsubscribe/<?php echo $pid; ?>/'" value="Unscribe">
			<?php } else { ?>
			<?php if ($tracking == FALSE) { ?>
			<input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base(); ?>feedback/subscribe/<?php echo $pid; ?>/'" value="Subscribe">
			<?php } ?>
			<?php } ?>
        </li>
	<?php } ?>
        <li style="width: 70px; display: inline;">
			<?php if ($tracking == TRUE) { ?>
            <input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base(); ?>feedback/untrack/<?php echo $uid; ?>/'" value="Untrack">
			<?php } else { ?>
            <input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base(); ?>feedback/track/<?php echo $uid; ?>/'" value="Track">
			<?php } ?>
        </li>
<?php } ?>
<?php
if ($this->uid == $uid && $this->uid != 1) {
?>
        <li style="width: 70px; display: inline;">
            <input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base() .'updates/add/'. $id .'/'; ?>'" value="Edit">
        </li>
        <li style="width: 70px; display: inline;">
            <input style="width: 70px;" type="button" onClick="parent.location='<?php echo url::base() .'updates/delete/'. $id .'/'; ?>'" value="Delete">
        </li>
<?php } ?>
    </ul>

</div>

<div style="float: right;">

    <ul style="display: inline;">
<?php
if (isset($first)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onClick="parent.location='<?php echo url::base() .'updates/view/'. $first .'/'; ?>'" value="<<">
        </li>
<? } ?>
<?php
if (isset($previous)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onClick="parent.location='<?php echo url::base() .'updates/view/'. $previous .'/'; ?>'" value="<">
        </li>
<? } ?>
<?php
if (isset($next)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onClick="parent.location='<?php echo url::base() .'updates/view/'. $next .'/'; ?>'" value=">">
        </li>
<? } ?>
<?php
if (isset($last)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onClick="parent.location='<?php echo url::base() .'updates/view/'. $last .'/'; ?>'" value=">>">
        </li>
<? } ?>
    </ul>
</div>

<div style="clear: both; margin-bottom: 10px;"></div>

<?php if (!empty($detail)) { ?>
<div style="border: 1px solid #AAA; margin-bottom: 10px; padding: 10px; padding-bottom: 0px;">
	<?php echo $detail; ?>
</div>
<?php } ?>

<?php if ($display == FALSE) { ?>
<div style="border: 2px solid #800; background-color: #FDD; margin: 10px; padding: 10px;">
	This update contains no further detail. Don't worry, there's nothing wrong with that!
</div>
<?php } ?>


<?php
// Is there an attached file we need to show?
if (!empty($filename)) {
	// Is it an image?
	if ($display == 'image') {
		echo '<div style="text-align: center;">';
		if (file_exists(DOCROOT .'uploads/files/'. $filename .'_fit.jpg'))
		{
			echo '<a href="'. url::base() .'uploads/files/'. $filename .'.'. $ext .'" rel="lightbox" title="'. $summary .'"><img src="'. url::base() .'uploads/files/'. $filename .'_fit.jpg"></a>';
		}
		else
		{
			echo '<a href="'. url::base() .'uploads/files/'. $filename .'.'. $ext .'" rel="lightbox" title="'. $summary .'"><img src="'. url::base() .'uploads/files/'. $filename .'.'. $ext .'"></a>';
		}
		echo '</div>';
	} elseif ($display == 'video') {
?>
<div style="margin: 10px; text-align: center;">
	<script type='text/javascript' src='/js/swfobject.js'></script>
		<div id='mediaspace'>This div will be replaced</div>
		<script type='text/javascript'>
		var s1 = new SWFObject('/player-viral.swf','ply','470','320','9','#ffffff');
		s1.addParam('allowfullscreen','true');
		s1.addParam('allowscriptaccess','always');
		s1.addParam('wmode','opaque');
		s1.addParam('flashvars','file=<?php echo $flv; ?>');
		s1.write('mediaspace');
	</script>
</div>
<?php
	} elseif ($display == 'sound') {
?>
<div style="margin: 10px; text-align: center;">
	<script type='text/javascript' src='/js/swfobject.js'></script>
		<div id='mediaspace'>This div will be replaced</div>
		<script type='text/javascript'>
		var s1 = new SWFObject('/player-viral.swf','ply','470','20','9','#ffffff');
		s1.addParam('allowfullscreen','true');
		s1.addParam('allowscriptaccess','always');
		s1.addParam('wmode','opaque');
		s1.addParam('flashvars','file=<?php echo $sound; ?>');
		s1.write('mediaspace');
	</script>
</div>
<?php }
} ?>
