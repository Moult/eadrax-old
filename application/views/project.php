<?php if (empty($uid) && empty($project['icon'])) { ?>
<div id="filter">
<ul class="block_links" style="float: left;">
<li><a href="<?php echo url::base(); ?>" class="block" <?php if ($filter == 'l') { echo 'style="background-color: #FF6500;" '; } ?>>Latest</a></li>
<li><a href="<?php echo url::base(); ?>projects/view/a/" class="block" <?php if ($filter == 'a') { echo 'style="background-color: #FF6500;" '; } ?>>Awesomest</a></li>
<li><a href="<?php echo url::base(); ?>projects/view/r/" class="block" <?php if ($filter == 'r') { echo 'style="background-color: #FF6500;" '; } ?>>Randomest</a></li>
</ul>

<ul class="block_links" style="float: right;">
<li><a href="<?php echo url::base(); ?>site/tour/" class="block" style="background-color: #444;">What is WIPUP?</a></li>
</ul>
</div>

<?php } elseif (!isset($join)) { ?>
<?php if (empty($project)) { ?>
<h2 style="float: left;">
<?php } else { ?>
<h2>
<?php } ?>
	<?php if (isset($category_name)) { ?>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" class="icon" alt="" />
	<?php } elseif (!empty($project['icon'])) { ?>
	<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="border: 1px solid #999; padding: 1px;" />
	<?php } else { ?>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" class="icon" alt="" />
	<?php } ?>
	<?php if (isset($category_name)) { ?>
	<?php echo $category_name; ?> Updates
	<?php } elseif (!empty($uid)) { ?>
		<?php if (empty($project)) { echo $u_name; } else { echo $project['name']; } ?>'s Updates <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>
	<?php } else { ?>
		Latest WIP Updates
	<?php } ?>
</h2>
<?php } ?>

<?php if (!isset($category_name)) { ?>
<?php if (!empty($project)) { ?>

<div style="float: left; font-size: 12px; color: #666; text-align: right; margin-bottom: 5px;">
By <a href="<?php echo url::base(); ?>profiles/view/<?php echo $u_name; ?>/"><?php echo $u_name; ?></a>
<?php if (!empty($contributors)) { ?> with <?php foreach ($contributors as $i => $contributor) { ?><?php echo $contributor; ?><?php if (!empty($aliases[$i])) { ?> <?php if (!empty($contributor)) { echo '('; } ?><?php if (!empty($match[$i])) { ?><a href="<?php echo url::base(); ?>profiles/view/<?php echo $aliases[$i]; ?>/"><?php } ?><?php echo $aliases[$i]; ?><?php if (!empty($match[$i])) { ?></a><?php if ($this->username == $aliases[$i]) { ?>[<a href="<?php echo url::base(); ?>projects/nocontribute/<?php echo $project['id']; ?>/">X</a>]<?php } ?><?php } ?><?php if (!empty($contributor)) { echo ')'; } ?><?php } ?>, <?php } ?><?php } ?>
in <em><?php echo $categories[$project['cid']]; ?></em> <?php if (!empty($project['website'])) { echo ' (<a href="'. $project['website'] .'">www</a>)'; } ?>
</div>

<div style="float: right; font-size: 12px; color: #666; text-align: right; margin-bottom: 5px;"><?php echo date('jS F Y', strtotime($project['logtime'])); ?></div>

<div style="clear: both; background-color: #FFF; border-top: 1px solid #888; padding: 10px; margin-bottom: 10px; padding-bottom: 1px; background-image: url('<?php echo url::base(); ?>images/comment_divide.png'); background-repeat: repeat-x; background-position: bottom;">
<?php echo $description; ?>
</div>
<?php } ?>
<?php } ?>

<?php if (empty($markup)) { ?>

<?php if ($this->uri->segment(1) == 'profiles') { ?>
<div style="clear: both; line-height: 23px; font-size: 15px; text-shadow: 0px 1px 0px #FFF; color: #555; border-bottom: 1px solid #999; border-left: 0px; border-right: 0px; padding: 8px; padding-top: 40px; padding-bottom: 40px; margin-bottom: 10px; line-height: 30px; text-align: center;">
	<img src="<?php echo url::base(); ?>images/icons/warning_48.png" alt="Oh no!" /><br />
<?php } else { ?>
<div class="error_message">
<?php } ?>
	Oh no! There're no updates to show just yet. You should <a href="<?php echo url::base(); ?>updates/add/">add something</a>, you know.
</div>

<?php } else { ?>
<?php if ($this->uri->segment(1) == 'profiles' && $this->uri->segment(2) == 'view') { ?>
<div style="clear: both; overflow: hidden; border-bottom: 1px dotted #AAA; padding-bottom: 5px; background-position: top; background-repeat: repeat-x;">
<?php } else { ?>
<div style="clear: both; overflow: hidden; border: 0px dotted #AAA; padding-bottom: 5px; background-position: top; background-repeat: repeat-x; border-left: 0px; border-right: 0px;">
<?php } ?>
	<div style="margin-right: auto; margin-left: auto; padding-right: 4px; width: 822px;">
		<?php echo $markup; ?>
	</div>
</div>
<?php } ?>

<?php if ($pages > 1) { ?>
<?php if (empty($project)) { $project['id'] = 0; } ?>
<?php if (empty($uid)) { $uid = 0; $project['id'] = 0; } ?>
<?php if (isset($category_name)) { $project['id'] = $category_id; } ?>
<div style="padding: 20px; margin-left: auto; margin-right: auto; clear: both; text-align: center;">
    <ul style="display: inline;">
<?php
if ($pages != 1 && $page > 1) {
?>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $uid .'/'. $project['id'] .'/1/'; ?>'" value="&lt;&lt;" />
        </li>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $uid .'/'. $project['id'] .'/'. ($page-1) .'/'; ?>'" value="&lt;" />
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

	$display = TRUE;

	// Pagination truncation
	if ($pages > 10) {
		if ($i > $page+4 || $i < $page-4) {
			if ($i != 1 && $i != $pages) {
				$display = FALSE;
			}
		}
	}

	if ($display == TRUE) {
		echo '<li style="display: inline;"><a href="'. url::base() .'projects/view/'. $uid .'/'. $project['id'] .'/'. $i .'/" style="'. $style .' text-decoration: none; padding: 8px; margin: 2px; margin-top: 0px; background: url('. url::base() .'images/formbg.gif); border: 1px solid #CCC; height: 25px; font-size: 10px;">'. $i .'</a></li>';
	}
}
?>
<?php
if ($pages != 1 && $page < $pages) {
?>
        <li style="width: 50px; display: inline;">
			<input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $uid .'/'. $project['id'] .'/'. ($page+1) .'/'; ?>'" value="&gt;" />
        </li>
        <li style="width: 50px; display: inline;">
            <input style="width: 50px;" type="button" onclick="parent.location='<?php echo url::base() .'projects/view/'. $uid .'/'. $project['id'] .'/'. $pages .'/'; ?>'" value="&gt;&gt;" />
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
