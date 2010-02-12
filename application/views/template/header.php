<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
/*
 * WIPUP is a website that allows you to show others what you are currently 
 * working on.
 *
 * Copyright 2009 (c)
 */
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <!-- Hacks and dirty IE Fixes for PNG Transparency -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/clear.js"></script>
        <!--<script type="text/javascript" src="<?php echo url::base(); ?>js/login.js"></script>-->
        <!--[if lt IE 7]>
        <script defer type="text/javascript" src="<?php echo url::base(); ?>js/pngfix.js"></script>
        <![endif]-->
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <!-- CSS styling and browser compatibility resets -->
        <link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>css/stylesheet.css" />
<?php
// If we are viewing an update, we need a couple more fancy stuff.
if ($this->uri->segment(1) == 'updates' && $this->uri->segment(2) == 'view') {
?>
        <!-- Lightbox support -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/prototype.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/scriptaculous.js?load=effects,builder"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/lightbox.js"></script>
        <link rel="stylesheet" href="<?php echo url::base(); ?>css/lightbox.css" type="text/css" media="screen" />
<?php }
// If we are viewing a project timeline, we again need more fancy stuff.
if ($this->uri->segment(1) == 'profiles') {
?>
        <!-- Scrollable support -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.scrollable-1.0.2.min.js"></script>
        <!-- Scrollable styles -->
        <link rel="stylesheet" href="<?php echo url::base(); ?>css/scrollable.css" type="text/css" media="screen" />
<?php }
// Certain forms need some BBCode fun!
if (($this->uri->segment(1) == 'updates' && $this->uri->segment(2) == 'add') || ($this->uri->segment(1) == 'projects' && $this->uri->segment(2) == 'add')) {
?>
		<!-- BBCode support -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/ed.js"></script>
		<!-- Resizing textarea -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.textarearesizer.compressed.js"></script>
<?php if ($this->uri->segment(1) == 'updates' && $this->uri->segment(2) == 'add') { ?>
		<!-- More file fields -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/browse.js"></script>
<?php } ?>

		<style type="text/css">
		div.grippie {
			background: #EEEEEE;
			border-color: #DDDDDD;
			border-style: solid;
			border-width: 0pt 1px 1px;
			cursor: s-resize;
			height: 5px;
			overflow: hidden;
			margin-left: 100px;
		}
		.resizable-textarea textarea {
			display:block;
			margin-bottom:0pt;
		}
		</style>

		<script type="text/javascript">
		/* jQuery textarea resizer plugin usage */
		$(document).ready(function() {
		$('textarea.resizable:not(.processed)').TextAreaResizer();
		});
		</script>
<?php } ?>
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="<?php echo url::base(); ?>images/favicon.png" />
        <title>WIPUP</title>
    </head>

	<body>

        <div id="container">
            <h1>
                <a href="<?php echo url::base(); ?>"><img src="<?php echo url::base(); ?>images/wipup.png" width="220" height="80" alt="WIPUP" /></a>
            </h1>

			<ul id="icon-navigation">
				<li><a href="<?php echo url::base(); ?>profiles/"><img src="<?php echo url::base(); ?>images/profile.png" width="39" height="40" alt="Profile" title="Profile" /></a></li>
                <li><a href="<?php echo url::base(); ?>updates/add/"><img src="<?php echo url::base(); ?>images/update.png" width="39" height="40" alt="Update" title="Update" /></a></li>
				<li><img src="<?php echo url::base(); ?>images/random.png" width="39" height="40" alt="Random" title="Random" /></li>
			</ul>

            <ul id="text-navigation">
				<li>What is WIPUP?</li>
				<li><?php if ($this->logged_in == TRUE) {?>Hey <?php echo $this->username; ?> (<a href="<?php echo url::base(); ?>dashboard/">Dashboard</a> | <a href="<?php echo url::base(); ?>users/logout/">Logout</a>)<?} else {?><a href="<?php echo url::base(); ?>users/login/">Login/Register</a><? } ?></li>
				<li>Search</li>
            </ul>

		<div id="content-top">
			<div id="content-left"></div>
			<div id="global-message">Version xx.02.10a</div>
			<div id="content-right"></div>
		</div>

        <div id="content">
            <div id="content_container">
                <!-- <CONTENT> -->
