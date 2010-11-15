<h2>
	<img src="<?php echo url::base(); ?>images/icons/spanner_48.png" width="48" height="48" class="icon" alt="" />
	<?php echo $summary; ?>
</h2>

<div style="float: left; color: #777; text-align: right; margin-bottom: 5px;">
	by <em><?php if ($uid != 1) { ?><a href="<?php echo url::base(); ?>profiles/view/<?php echo $user_information['username']; ?>/"><?php echo $user_information['username']; ?></a><?php } else { ?>Guest<?php } ?></em> with <span style="font-weight: bold;"><?php echo $kudos; ?></span> kudos
</div>

<div style="float: right; color: #AAA; text-align: right; margin-bottom: 5px;"><?php echo date('jS F Y', strtotime($logtime)); ?></div>

<div style="clear: both;"></div>

<div style="display: none;"><div id="data"><?php echo $share; ?></div></div>


<div style="float: left; margin-bottom: 10px;">
    <ul style="margin-left: 0px; display: inline;">
        <li style="width: 70px; display: inline;">
			<a id="inline" href="#data"><input style="width: 70px;" type="button" value="Share" /></a>
        </li>
<?php if (isset($feature)) { ?>
        <li style="width: 70px; display: inline;">
			<input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>updates/feature/<?php echo $id; ?>/'" value="Feature" />
        </li>
<?php } ?>
<?php if ($uid != 1 && $uid != $this->uid) { ?>
        <li style="width: 70px; display: inline;">
<?php if (isset($kudos_error)) { ?>
			<input style="width: 70px;" type="button" value="Kudos'd!" disabled="disabled" />
<?php } else { ?>
			<input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/kudos/<?php echo $id; ?>/'" value="Kudos" />
<?php } ?>
        </li>
<?php } ?>
<?php if ($uid != 1) { ?>
	<?php if ($pid != 1) { ?>
        <li style="width: 70px; display: inline;">
			<?php if ($subscribed == TRUE) { ?>
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/unsubscribe/<?php echo $pid; ?>/'" value="Unscribe" />
			<?php } elseif ($tracking == FALSE && $uid != $this->uid ) { ?>
			<input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/subscribe/<?php echo $pid; ?>/'" value="Subscribe" />
			<?php } ?>
        </li>
	<?php } ?>
        <li style="width: 70px; display: inline;">
			<?php if ($tracking == TRUE) { ?>
            <input style="width: 70px;" type="button" onclick="parent.location='<?php echo url::base(); ?>feedback/untrack/<?php echo $uid; ?>/'" value="Untrack" />
			<?php } elseif ($tracking == FALSE && $uid != $this->uid && $user_information['enable_tracking'] == 1) { ?>
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
<div style="clear: both; background-color: #FFF; border-top: 1px solid #888; padding: 10px; margin-bottom: 10px; padding-bottom: 1px; background-image: url('<?php echo url::base(); ?>images/comment_divide.png'); background-repeat: repeat-x; background-position: bottom;">
	<?php echo $detail; ?>
</div>
<?php } ?>

<?php if ($no_of_files == 0 && (empty($detail))) { ?>
<div class="error_message">
	This update contains no further description or attachments. Don't worry, there's nothing wrong with that!
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
?>

<script type="text/javascript">
$(document).ready(function() {
	$("#single_image<?php echo $i; ?>").mouseenter(function(){
		$("#single_cite<?php echo $i; ?>").animate({opacity: 0.7}, 300);
	});
	$("#single_image<?php echo $i; ?>").mouseleave(function(){
		$("#single_cite<?php echo $i; ?>").animate({opacity: 0}, 300);
	});
});
</script>
<?php
			echo '<cite id="single_cite'. $i .'" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color: #000; color: #FFF; position: relative; margin-left: auto; margin-right: auto; top: 40px; height: 15px; padding: 10px; font-weight: bold; opacity: 0; z-index: 1;">This image has been scaled down, click to view the image full size.</cite>';
			echo '<a id="single_image'. $i .'" class="single_image" href="'. url::base() .'uploads/files/'. ${'filename'. $i} .'.'. ${'ext'. $i} .'"><img style="vertical-align: middle; border: 1px solid #999; padding: 1px;" src="'. url::base() .'uploads/files/'. ${'filename'. $i} .'_fit.jpg" alt="'. $summary .' (attachment #'. ($i + 1) .')" title="'. $summary .'" /></a>';
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
<div style="border: 1px solid #88AAFF; margin-left: auto; margin-right: auto; margin-top: 10px; background-color: #DDEEFF; padding: 10px;">
		<div style="float: left; width: 100px;">
			<p style="font-size: 18px; margin-bottom: 0px; line-height: 63px;">
				<img src="<?php echo ${'filename_icon'. $i}; ?>" class="icon" alt="" <?php if (!strpos(${'filename_icon'. $i}, 'images/icons')) { echo 'style="border: 1px solid #999; padding: 1px;"'; } ?> />
			</p>
        </div>

        <div style="float: left;">
			<p style="font-size: 18px; margin-top: 5px; margin-bottom: 5px;">
				<a href="<?php echo url::base(); ?>updates/view/<?php echo $id; ?>/<?php echo $i; ?>/"><strong>Download</strong> <?php echo substr(${'filename'. $i}, 10) .'.'. ${'ext'. $i}; ?></a>
			</p>
            <p style="color: #555; margin-bottom: 0;">
                Size: <?php echo ${'file_size'. $i}; ?><br />
                Date: <?php echo date('jS F Y', strtotime($logtime)); ?><br />
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>
<?php } ?>

<?php }
	if ($no_of_files > 1) { ?>
	</div>
<?php } } ?>

<?php if ($no_of_files > 1) { ?>
</div></div>
<?php } ?>
