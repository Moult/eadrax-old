<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<!--

/*
 *
 * WIPUP is a website that allows you to show others what you are currently 
 * working on.
 *
 * Copyright 2009 (c)
 *
 */

-->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <!-- Hacks and dirty IE Fixes for PNG Transparency -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/clear.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/login.js"></script>
        <!--[if lt IE 7]>
        <script defer type="text/javascript" src="<?php echo url::base(); ?>js/pngfix.js"></script>
        <![endif]-->
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <!-- CSS styling and browser compatibility resets -->
        <link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>css/stylesheet.css" />
<?php
// If we are viewing an update, we need a couple more fancy stuff.
?>
        <!-- Lightbox support -->
        <script type="text/javascript" src="/js/prototype.js"></script>
        <script type="text/javascript" src="/js/scriptaculous.js?load=effects,builder"></script>
        <script type="text/javascript" src="/js/lightbox.js"></script>
        <link rel="stylesheet" href="/css/lightbox.css" type="text/css" media="screen" />
<?php  ?>
<?php
// If we are viewing a project timeline, we again need more fancy stuff.
?>
        <!-- Scrollable support -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script src="<?php echo url::base(); ?>js/jquery.mousewheel.js"></script>
        <script src="<?php echo url::base(); ?>js/jquery.scrollable-1.0.2.min.js"></script>
        <!-- Scrollable styles -->
        <link rel="stylesheet" href="<?php echo url::base(); ?>css/scrollable.css" type="text/css" media="screen" />
<?php ?>
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="<?php echo url::base(); ?>images/favicon.png" />
        <title>WIPUP</title>
    </head>

	<body>

        <div id="container">
            <h1 id="title">
				Title and logo to be redone.<br /><a href="<?php echo url::base(); ?>">Go to homepage</a>
                <!--<img src="<?php echo url::base(); ?>images/wipup.png" width="203" height="50" alt="WIPUP" />-->
            </h1>

            <ul id="navigation">
				<li><a href="<?php echo url::base(); ?>dashboard/"><img src="<?php echo url::base(); ?>images/Dashboard.png" width="48" height="48" alt="Dashboard" title="Dashboard" /></a></li>
                <li><a href="<?php echo url::base(); ?>profiles/"><img src="<?php echo url::base(); ?>images/Profile.png" width="48" height="48" alt="Profile" title="Profile" /></a></li>
                <li><a href="<?php echo url::base(); ?>updates/add/"><img src="<?php echo url::base(); ?>images/Update.png" width="48" height="48" alt="Update" title="Update" /></a></li>
                <li><img src="<?php echo url::base(); ?>images/Search.png" width="48" height="48"
                alt="Search" title="Search" /></li>
            </ul>

            <div id="upload_box">
                <div id="upload_form">
                    <img src="<?php echo url::base(); ?>images/why.png" width="180" height="19" id="why_message"
                    alt="Why get an account?" />
                    <div id="help_icon"></div>

                    <form action="" name="quickup" method="post">
                        <div id="upload_description">
                            <input type="text" name="upload_description" value="The QuickUp doesn't work at the moment. Sorry!" onFocus="quickup_summary()" style="color: #AAAAAA;" />
                        </div>

                        <div id="upload_file">
                            <input type="file" name="upload" size="30" />
                        </div>

                        <div id="upload">
                            <input type="image" style="border: 0;" src="<?php echo url::base(); ?>images/upload.png" alt="Upload" />
                        </div>
                    </form>
                </div>

                <div id="login_form">
                    <div id="login_form_title">
						Log in or <a href="<?php echo url::base(); ?>users/register/">Register</a>
                    </div>
					<form action="<?php echo url::base(); ?>users/login/" name="login_form" method="post">
                        <div id="username_field">
							<input type="text" id="openid_identifier" name="openid_identifier" value="Username" onFocus="login_username()" style="color: #AAAAAA;" />
                        </div>
                        <div id="terms">
							(<a href="<?php echo url::base(); ?>site/legal/">terms of use</a>)
                        </div>
                        <div id="password_field">
							<input type="password" id="password" name="password" value="Password" onFocus="login_password()" style="color: #AAAAAA;" />
                        </div>
                        <div id="signin">
                            <input type="image" style="border: 0;" src="<?php echo url::base(); ?>images/signin.png" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="bar">
            <div class="bar_text">
                <a href="#">Learn more about WIPUP</a> &#8226; Last updated 9th September. Still UNDER CONSTRUCTION
            </div>

            <form action="" method="post">
                <div id="search_button">
                    <input type="image" style="border: 0;" src="<?php echo url::base(); ?>images/search_go.png" />
                </div>
                <div id="search_field">
                    <input type="text" name="search" />
                </div>
            </form>
        </div>

        <div id="content">
            <div id="content_container">
                <!-- <CONTENT> -->
