<h2>
	<?php if (!empty($project['icon'])) { ?>
	<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
	<?php } else { ?>
	<img src="<?php echo url::base(); ?>images/icons/coffee_mug.png" width="48" height="48" class="icon" alt="" />
	<?php } ?>
	<?php echo $project['name']; ?>'s Updates <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>
</h2>

<div style="float: left;font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;">
Category: <em><?php echo $categories[$project['cid']]; ?></em> <?php if (!empty($project['website'])) { echo ' (<a href="'. $project['website'] .'">www</a>)'; } ?>
</div>

<div style="float: right; font-size: 18px; letter-spacing: -1px; color: #AAA; text-align: right; margin-bottom: 5px;"><?php echo $project['logtime']; ?></div>

<div style="clear: both; background-color: #FFF; border: 1px solid #888; padding: 10px; margin-bottom: 10px; padding-bottom: 0px;">
<?php echo $description; ?>
</div>

<?php if (empty($markup)) { ?>

<div style="border: 2px solid #800; background-color: #FDD; margin: 10px; padding: 10px;">
	Oh no! There's no updates in this project just yet. You should add something, you know.
</div>

<?php } else { echo $markup; } ?>

<?php if ($pages > 1) { ?>
<div style="padding: 20px; margin-left: auto; margin-right: auto; clear: both; text-align: center;">
    <ul style="display: inline;">
<?php
if ($pages != 1 && $page > 1) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $project['id'] .'/1/'; ?>'" value="&lt;&lt;" />
        </li>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $project['id'] .'/'. ($page-1) .'/'; ?>'" value="&lt;" />
        </li>
<? } else { ?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px; color: #CCC;" type="button" value="&lt;&lt;" />
        </li>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px; color: #CCC;" type="button" value="&lt;" />
        </li>
<?php
}
for ($i = 1; $i <= $pages; $i++) {
	if ($i == $page) {
		$style = 'color: #000;';
	} else {
		$style = '';
	}
	echo '<li style="display: inline; padding: 8px; margin: 2px; margin-top: 0px; background: url('. url::base() .'images/formbg.gif); border: 1px solid #CCC; height: 25px; font-size: 10px;"><a href="'. url::base() .'projects/view/'. $project['id'] .'/'. $i .'/" style="'. $style .' text-decoration: none;">'. $i .'</a></li>';
}
?>
<?php
if ($pages != 1 && $page < $pages) {
?>
        <li style="width: 50px; display: inline;">
			<input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $project['id'] .'/'. ($page+1) .'/'; ?>'" value="&gt;" />
        </li>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $project['id'] .'/'. $pages .'/'; ?>'" value="&gt;&gt;" />
        </li>
<? } else { ?>
        <li style="width: 50px; display: inline;">
			<input style="width: 50px; color: #CCC;" type="button" value="&gt;" />
        </li>
        <li style="width: 50px; display: inline;">
			<input style="width: 50px; color: #CCC;" type="button" value="&gt;&gt;" />
        </li>
<?php } ?>
    </ul>
</div>
<?php } ?>
