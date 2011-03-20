<!-- For social sharing purposes -->
<head>
<meta name="title" content="WIPUP - Share your ambitions" />
<meta name="description" content="<?php echo $summary .' - '. $detail; ?>" />
<link rel="image_src" href="<?php echo substr($filename_icon0, 0, -4) .'_crop.jpg'; ?>" />
<meta property="image" content="<?php echo substr($filename_icon0, 0, -4) .'_crop.jpg'; ?>" />
<meta property="og:image" content="<?php echo substr($filename_icon0, 0, -4) .'_crop.jpg'; ?>" />
</head>
<h2>
	<div style="float: left;">
		<img src="<?php echo url::base(); ?>images/icons/spanner_48.png" width="48" height="48" class="icon" alt="" />
<!--
		<?php $icon = Updates_Controller::_file_icon($filename0, $ext0); ?>
		<?php if (!strpos($icon, 'images/icons')) { $markup_add = '-moz-box-shadow: 1px 1px 3px #555; -webkit-box-shadow: 1px 1px 3px #555; box-shadow: 1px 1px 3px #555; padding: 2px; background-color: #FFF;'; } else { $markup_add = ''; } ?>
		<?php echo '<a href="'. url::base() .'updates/view/'. $id .'/"><img style="vertical-align: middle; '. $markup_add .'" src="'. $icon .'" alt="update icon" /></a></p>'; ?>
	-->
	</div>

	<div style="float: left; margin-left: 5px;">
		<?php echo $summary; ?>

		<?php if ($this->uid == $uid && $this->uid != 1) { ?>
            <a href="<?php echo url::base() .'updates/add/'. $id .'/'; ?>"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a>
		<?php } ?>


		<div style="margin-top: 0px; font-weight: bold; color: #555; letter-spacing: 0px; font-size: 12px;">
<?php if (isset($project_information)) { ?>
By <?php if ($uid != 1) { ?><a href="<?php echo url::base(); ?>profiles/view/<?php echo $user_information['username']; ?>/"><?php echo $user_information['username']; ?></a><?php } else { ?>Guest<?php } ?> from <a href="<?php echo url::base(); ?>projects/view/<?php echo $project_information['uid']; ?>/<?php echo $project_information['id']; ?>/"><?php echo $project_information['name']; ?></a> - <?php echo $project_information['summary']; ?>
<?php } else { ?>
By <?php if ($uid != 1) { ?><a href="<?php echo url::base(); ?>profiles/view/<?php echo $user_information['username']; ?>/"><?php echo $user_information['username']; ?></a><?php } else { ?>Guest<?php } ?> from <a href="<?php echo url::base(); ?>projects/view/<?php echo $uid; ?>/1/">Uncategorised Updates</a> - Updates that are not part of a long-term project
<?php } ?>
		</div>

	</div>

	<div style="float: right; letter-spacing: 0px; color: #999; font-size: 10px; font-weight: 0; margin-top: 30px;">
		<?php echo date('jS F Y', strtotime($logtime)); ?>
	</div>
</h2>

<div style="clear: both; margin-bottom: 5px;"></div>

<div style="display: none;"><div id="data"><?php echo $share; ?></div></div>

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
			<li><a href="#fragment-<?php echo $i; ?>"><img src="<?php echo ${'filename_icon'. $i}; ?>" <?php if (${'display'. $i} == 'image' || ${'display'. $i} == 'video') { ?>style="-moz-box-shadow: 1px 1px 3px #555; -webkit-box-shadow: 1px 1px 3px #555; box-shadow: 1px 1px 3px #555; padding: 2px;" <?php } ?>alt="attachment<?php echo $i; ?>" /></a></li>
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
				<img src="<?php echo ${'filename_icon'. $i}; ?>" class="icon" alt="" <?php if (!strpos(${'filename_icon'. $i}, 'images/icons')) { echo 'style="-moz-box-shadow: 1px 1px 3px #555; -webkit-box-shadow: 1px 1px 3px #555; box-shadow: 1px 1px 3px #555; padding: 2px; background-color: #FFF;"'; } ?> />
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

<div style="text-align: center; margin-top: 10px;">
    <ul style="display: inline; margin: 0px;">
<?php
if (isset($first)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $first .'/'; ?>'" value="&lt;&lt;" />
        </li>
<?php } ?>
<?php
if (isset($previous)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $previous .'/'; ?>'" value="&lt;" />
        </li>
<?php } ?>
<?php
if (isset($next)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $next .'/'; ?>'" value="&gt;" />
        </li>
<?php } ?>
<?php
if (isset($last)) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'updates/view/'. $last .'/'; ?>'" value="&gt;&gt;" />
        </li>
<?php } ?>
    </ul>
</div>

