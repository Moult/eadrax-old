<?php if (empty($uid) && empty($project['icon'])) { ?>

<?php if ($this->logged_in == FALSE) { ?>
<div style="width: 510px; float: left;">
<div style="font-family: 'Signika'; font-size: 40px; color: #333; font-weight: 600; letter-spacing: -3px; margin-bottom: 25px;"><span style="font-size: 50px;">Share. Critique. Track.</span><br />Show us what you're working on</div>
<img style="float: left;" src="<?php echo url::base(); ?>images/heart.png" alt="" />
<p style="font-weight: bold; color: #222; margin-bottom: 20px; float: left; margin-top: 4px; margin-left: 10px;">
Completely free, forever. No upload restrictions. Track interesting projects.<br />
Personal profile. Statistics. Collaborative projects. Versioned pastebins.
</p>

</div>

<div style="float: left; width: 340px;">
	<div class="form" style="clear: both;">
		<form action="<?php echo url::base(); ?>users/login/" method="post">
			<fieldset>
				<legend>
					<img src="<?php echo url::base(); ?>images/icons/drive_user.png" alt="" width="16" height="16" class="icon" />
					Log in or Register
				</legend>
				<div class="elements">
					<p>
						<label for="openid_identifier" title="WIPUP allows you to use OpenID-enabled accounts to sign in, such as Google, Facebook, Twitter, and Wordpress. Just click the OpenID icon to get started.">Username:
							<a class="rpxnow" onclick="return false;" href="https://wipup.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2F<?php echo substr(url::base(), 7); ?>users%2Frpx%2F"><img src="<?php echo url::base(); ?>images/icons/openid.gif" class="icon" /></a>
						</label>
						<input type="text" id="openid_identifier" name="openid_identifier" <?php if (isset($errors['openid_identifier'])) { echo 'class="error"'; } ?> />
					</p>

					<p>
						<label for="password">Password:</label>
						<input type="password" id="password" name="password" />
					</p>



					<p class="submit">
						<input type="submit" name="submit" class="submit" value="Start sharing my WIPs" />
					</p>

					<p style="font-size: 11px; margin-bottom: 0px; margin-top: 15px;">
						<input type="checkbox" id="remember" name="remember" /> Keep me signed in
						<span style="float: right;">
						<span title="WIP means work-in-progress. A WIP update can be anything from a single sentence to downloadable files with embedded videos."><a href="#"><strong>What is a WIP?</strong></a></span> - <a href="<?php echo url::base() .'site/legal/'; ?>">Boring legal stuff</a>
						</span>
					</p>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<?php } ?>

<?php } elseif (!isset($join)) { ?>
<?php if (empty($project)) { ?>
<h2 style="float: left;">
<?php } else { ?>
<h2>
<?php } ?>
	<?php if (isset($category_name)) { ?>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" class="icon" alt="" />
	<?php } elseif (!empty($project['icon'])) { ?>
	<img src="<?php echo url::base(); ?>uploads/icons/<?php echo $project['icon']; ?>" class="icon" alt="" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; position: relative; top: 1px; border: 1px solid #333;" />
	<?php } else { ?>
	<img src="<?php echo url::base(); ?>images/icons/folder_48.png" class="icon" alt="" />
	<?php } ?>
	<?php if (isset($category_name)) { ?>
	<?php echo $category_name; ?>
	<?php } elseif (!empty($uid)) { ?>
		<?php if (empty($project)) { echo $u_name; } else { echo $project['name']; } ?> <?php if ($project['uid'] == $this->uid && $project['uid'] != 1) { ?><a href="<?php echo url::base(); ?>projects/add/<?php echo $project['id']; ?>/"><img src="<?php echo url::base(); ?>images/icons/pencil.png" class="icon" alt="Edit" /></a><?php } ?>
		<div style="float: right; text-shadow: none; font-size: 10px; letter-spacing: 0px;">

<div id="filter" style="overflow: hidden; margin-top: 10px;">
<ul class="block_links" style="float: left;">
<?php if ($uid != 1) { ?>
	<?php if ($project['id'] != 1) { ?>
        <li style="width: 70px; display: inline;">
			<?php if ($subscribed == TRUE) { ?>
			<li><a href="<?php echo url::base(); ?>feedback/unsubscribe/<?php echo $project['id'];?>/" class="block">Unsubscribe</a></li>
			<?php } elseif ($tracking == FALSE && $uid != $this->uid ) { ?>
			<li><a href="<?php echo url::base(); ?>feedback/subscribe/<?php echo $project['id'];?>/" class="block">Subscribe</a></li>
			<?php } ?>
	<?php } ?>
<?php } ?>

</ul></div>

		</div>
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
	<div style="overflow: hidden; margin-right: auto; margin-left: auto;">
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
<?php } else { ?>
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
<?php } else { ?>
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
