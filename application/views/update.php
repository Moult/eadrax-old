<h2>
	<img src="<?php echo url::base(); ?>images/icons/spanner_48.png" width="48" height="48" class="icon" alt="" />
	<?php echo $summary; ?>
</h2>

<div style="float: left;font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;">
	<?php if ($uid != 1) { ?>
	Viewing Project: <em><?php if ($pid != 1) { ?><a href="<?php echo url::base(); ?>projects/view/<?php echo $uid; ?>/<?php echo $pid; ?>"><?php echo $project_information['name']; ?></a><?php } else { ?>Uncategorised<?php } ?></em>
	<?php } ?>
	by <em><?php if ($uid != 1) { ?><a href="<?php echo url::base(); ?>profiles/view/<?php echo $uid; ?>/"><?php echo $user_information['username']; ?></a><?php } else { ?>Guest<?php } ?></em> with <?php echo $kudos; ?> kudos
</div>

<div style="float: right; font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;"><?php echo date('jS F Y', strtotime($logtime)); ?></div>

<div style="clear: both;"></div>


<div style="float: left; margin-bottom: 10px;">

    <ul style="margin-left: 0px; display: inline;">
<?php if ($uid != 1 && $uid != $this->uid) { ?>
        <li style="width: 70px; display: inline;">
			<input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/kudos/<?php echo $id; ?>/'" value="Kudos" />
        </li>
<?php } ?>
<?php if ($uid != 1) { ?>
	<?php if ($pid != 1) { ?>
        <li style="width: 70px; display: inline;">
			<?php if ($subscribed == TRUE) { ?>
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/unsubscribe/<?php echo $pid; ?>/'" value="Unscribe" />
			<?php } else { ?>
			<?php if ($tracking == FALSE && $uid != $this->uid) { ?>
			<input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/subscribe/<?php echo $pid; ?>/'" value="Subscribe" />
			<?php } ?>
			<?php } ?>
        </li>
	<?php } ?>
        <li style="width: 70px; display: inline;">
			<?php if ($tracking == TRUE) { ?>
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/untrack/<?php echo $uid; ?>/'" value="Untrack" />
			<?php } elseif ($tracking == FALSE && $uid != $this->uid) { ?>
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/track/<?php echo $uid; ?>/'" value="Track" />
			<?php } ?>
        </li>
<?php } ?>
<?php
if ($this->uid == $uid && $this->uid != 1) {
?>
        <li style="width: 70px; display: inline;">
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/add/'. $id .'/'; ?>'" value="Edit" />
        </li>
        <li style="width: 70px; display: inline;">
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/delete/'. $id .'/'; ?>'" value="Delete" />
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
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $first .'/'; ?>'" value="&lt;&lt;" />
        </li>
<? } ?>
<?php
if (isset($previous)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $previous .'/'; ?>'" value="&lt;" />
        </li>
<? } ?>
<?php
if (isset($next)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $next .'/'; ?>'" value="&gt;" />
        </li>
<? } ?>
<?php
if (isset($last)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $last .'/'; ?>'" value="&gt;&gt;" />
        </li>
<? } ?>
    </ul>
</div>

<div style="clear: both; margin-bottom: 10px;"></div>

<?php if (!empty($detail)) { ?>
<div style="border: 1px solid #AAA; margin-bottom: 10px; padding: 10px; padding-bottom: 0px; background-color: #FFFFFF;">
	<?php echo $detail; ?>
</div>
<?php } ?>

<?php if ($no_of_files == 0 && (empty($detail))) { ?>
<div style="border: 2px solid #800; background-color: #FDD; margin: 10px; padding: 10px;">
	This update contains no further detail. Don't worry, there's nothing wrong with that!
</div>
<?php } elseif ($no_of_files > 1) { ?>
<div id="file-wrap">
	<div id="tabs">
		<ul>
<?php for ($i=0; $i<5; $i++) { if(!empty(${'filename'. $i})) { ?>
			<li><a href="#fragment-<?php echo $i; ?>"><img src="<?php echo ${'filename_icon'. $i}; ?>" <?php if (${'display'. $i} == 'image' || ${'display'. $i} == 'video') { ?>style="border: 1px solid #888; padding: 2px;" <?php } ?>alt="attachment<?php echo $i; ?>" /></a></li>
<?php } } ?>
		</ul>
<?php } ?>

<?php
// Is there an attached file we need to show?
for ($i=0; $i<5; $i++)
{
	if ($no_of_files > 1 && $i == 0) { ?>
		<div id="fragment-<?php echo $i; ?>" class="ui-tabs-panel">
<?php } elseif ($no_of_files > 1) { ?>
		<div id="fragment-<?php echo $i; ?>" class="ui-tabs-panel ui-tabs-hide">
<?php }

	if (!empty(${'filename'. $i})) {
		// Is it an image?
		if (${'display'. $i} == 'image') {
			echo '<div style="text-align: center;">';
			if (file_exists(DOCROOT .'uploads/files/'. ${'filename'. $i} .'_fit.jpg'))
			{
				echo '<a class="single_image" href="'. url::base() .'uploads/files/'. ${'filename'. $i} .'.'. ${'ext'. $i} .'" title="'. $summary .'"><img src="'. url::base() .'uploads/files/'. ${'filename'. $i} .'_fit.jpg" style="border: 2px solid #DDD; position: relative; left: -2px;" alt="attachment'. $i .'" /></a>';
				echo '<cite style="background: #000000; -moz-opacity:.55; filter:alpha(opacity=55); opacity: .55; color: #FFF; position: relative; display: block; margin-left: auto; margin-right: auto; top: -42px; height: 15px; width: 810px; padding: 10px; border-top: 2px solid #FFF; font-weight: bold;">This image is scaled down, click to view image full size.</cite>';
			}
			else
			{
				echo '<a class="single_image" href="'. url::base() .'uploads/files/'. ${'filename'. $i} .'.'. ${'ext'. $i} .'" title="'. $summary .'"><img src="'. url::base() .'uploads/files/'. ${'filename'. $i} .'.'. ${'ext'. $i} .'" style="background: url(http://localhost/eadrax/images/shadow-1000x1000.gif) no-repeat right bottom; padding: 5px 10px 10px 5px;" alt="attachment'. $i .'" /></a>';
			}
			echo '</div>';
		} elseif (${'display'. $i} == 'video') {
?>
<div style="margin: 10px; text-align: center;">
	<script type='text/javascript' src='<?php echo url::base(); ?>js/swfobject.js'></script>
		<div id='mediaspace'>This div will be replaced</div>
		<script type='text/javascript'>
		var s1 = new SWFObject('<?php echo url::base(); ?>player.swf','ply','470','320','9','#ffffff');
		s1.addParam('allowfullscreen','true');
		s1.addParam('allowscriptaccess','always');
		s1.addParam('wmode','opaque');
		s1.addParam('flashvars','file=<?php echo url::base(); ?>uploads/files/<?php echo ${'filename'. $i}; ?>.<?php echo ${'ext'. $i};?>');
		s1.write('mediaspace');
	</script>
</div>
<?php
	} elseif (${'display'. $i} == 'sound') {
?>
<div style="margin: 10px; text-align: center;">
	<script type='text/javascript' src='<?php echo url::base(); ?>js/swfobject.js'></script>
		<div id='mediaspace'>This div will be replaced</div>
		<script type='text/javascript'>
		var s1 = new SWFObject('<?php echo url::base(); ?>player.swf','ply','470','20','9','#ffffff');
		s1.addParam('allowfullscreen','true');
		s1.addParam('allowscriptaccess','always');
		s1.addParam('wmode','opaque');
		s1.addParam('flashvars','file=<?php echo url::base(); ?>uploads/files/<?php echo ${'filename'. $i}; ?>.<?php echo ${'ext'. $i};?>');
		s1.write('mediaspace');
	</script>
</div>
<?php } ?>

<?php if (${'display'. $i} == 'download' || ${'display'. $i} == 'video' || ${'display'. $i} == 'sound') { ?>
<div style="border: 2px solid #88AAFF; margin-left: auto; margin-right: auto; margin-top: 10px; font-size: 18px; background-color: #DDEEFF; padding: 10px;">
		<div style="float: left; width: 61%;">
			<p style="font-size: 18px; margin-bottom: 0px; line-height: 63px;">
				<img src="<?php echo ${'filename_icon'. $i}; ?>" class="icon" alt="" <?php if (!strpos(${'filename_icon'. $i}, 'images/icons')) { echo 'style="border: 1px solid #999; padding: 1px;"'; } ?> />
				<a href="<?php echo url::base(); ?>uploads/files/<?php echo ${'filename'. $i}; ?>.<?php echo ${'ext'. $i}; ?>"><strong>Download</strong> <?php echo substr(${'filename'. $i}, 10) .'.'. ${'ext'. $i}; ?></a>
			</p>
        </div>

        <div style="width: 300px; float: right; height: 63px;">
            <p style="font-size: 18px; color: #555; margin-bottom: 0;">
                Size: <?php echo ${'file_size'. $i}; ?> <?php echo ${'file_size_ext'. $i}; ?><br />
                Date: <?php echo $logtime; ?><br />
                By: <?php if ($uid != 1) { ?><a href="<?php echo url::base(); ?>profile/view/<?php echo $uid; ?>/"><?php echo $user_information['username']; ?></a><?php } else { ?>Guest<?php } ?>
            </p>
        </div>
        <div style="clear: both; padding: 0; margin: 0;"></div>
    </div>
<?php } ?>

<?php }
	if ($no_of_files > 1) { ?>
	</div>
<?php } } ?>

<?php if ($no_of_files > 1) { ?>
</div></div>
<?php } ?>
