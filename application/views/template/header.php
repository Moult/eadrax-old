<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
/*
 * WIPUP is a website that allows you to show others what you are currently 
 * working on.
 *
 * Copyright 2010 (c)
 */
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <!-- Hacks and dirty IE Fixes for PNG Transparency -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/clear.js"></script>
        <!--[if lt IE 7]>
        <script defer type="text/javascript" src="<?php echo url::base(); ?>js/pngfix.js"></script>
        <![endif]-->
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <!-- CSS styling and browser compatibility resets -->
        <link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>css/stylesheet.css" />
		<!-- Let's load JQuery! -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

		<!-- Tooltip support -->
		<script src="http://cdn.jquerytools.org/1.2.3/full/jquery.tools.min.js"></script>

		<style>
		.tooltip {
			background-color:#000;
			border:1px solid #fff;
			padding:10px 15px;
			width:300px;
			display:none;
			color:#fff;
			text-align:left;
			font-size:12px;

			/* outline radius for mozilla/firefox/css3-complaint only */
			-moz-box-shadow:0 0 10px #000;
			-webkit-box-shadow:0 0 10px #000;
			box-shadow:0 0 10px #000;
		}
		</style>

		<script>
		$(function() {
			$("span[title]").tooltip({
				position: "bottom center",
				offset: [10, 0],
				effect: "fade",
				opacity: 0.8
			});
		});
		</script>

		<script>
		$(function() {
			$("label[title]").tooltip({
				position: "bottom center",
				offset: [10, 0],
				effect: "fade",
				opacity: 0.8
			});
		});
		</script>
<?php
// If we are viewing an update, we need a couple more fancy stuff.
if ($this->uri->segment(1) == 'updates' && ($this->uri->segment(2) == 'view' || $this->uri->segment(2) == 'random')) {
?>
        <!-- Fancybox support -->
		<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.fancybox-1.3.0.pack.js"></script>
		<link rel="stylesheet" href="<?php echo url::base(); ?>css/jquery.fancybox-1.3.0.css" type="text/css" media="screen" />

        <!-- Scrollable support -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/scrollable.min.js"></script>
		<script type="text/javascript" src="<?php echo url::base(); ?>js/scrollable.js"></script>
        <link rel="stylesheet" href="<?php echo url::base(); ?>css/scrollable.css" type="text/css" media="screen" />

		<!-- Resizing textarea -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.textarearesizer.compressed.js"></script>

		<style type="text/css">
		div.grippie {
			background: #EEEEEE;
			border-color: #DDDDDD;
			border-style: solid;
			border-width: 0pt 1px 1px;
			cursor: s-resize;
			height: 5px;
			overflow: hidden;
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

		<script type="text/javascript">
		$(document).ready(function() {
			$("a.single_image").fancybox({
				'transitionIn'	: 'elastic',
				'easingIn'		: 'swing',
				'autoScale'		: false
			});

			$("a#inline").fancybox({
				'hideOnContentClick': false
			});

		});
		</script>

        <!-- Multiple file view support -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.7.custom.min.js"></script>
        <script type="text/javascript">
            $(function() {
                var $tabs = $('#tabs').tabs();
            });
        </script>
<?php }
// If we are viewing a project timeline, we again need more fancy stuff.
if ($this->uri->segment(1) == 'profiles') {
?>

<?php if ($this->uri->segment(2) == 'projects') { ?>
        <!-- Scrollable support -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="<?php echo url::base(); ?>js/scrollable.min.js"></script>
		<script type="text/javascript" src="<?php echo url::base(); ?>js/scrollable.js"></script>
        <!-- Scrollable styles -->
        <link rel="stylesheet" href="<?php echo url::base(); ?>css/scrollable.css" type="text/css" media="screen" />

<?php } elseif ($this->uri->segment(2) == 'view') { ?>
		<!-- Tooltip support -->
		<script src="http://cdn.jquerytools.org/1.2.3/full/jquery.tools.min.js"></script>

		<style>
		.tooltip {
			background-color:#000;
			border:1px solid #fff;
			padding:10px 15px;
			width:600px;
			display:none;
			color:#fff;
			text-align:left;
			font-size:12px;

			/* outline radius for mozilla/firefox/css3-complaint only */
			-moz-box-shadow:0 0 10px #000;
			-webkit-box-shadow:0 0 10px #000;
			box-shadow:0 0 10px #000;
		}
		</style>
<?php } }
// Certain forms need some BBCode fun!
if (($this->uri->segment(1) == 'updates' && $this->uri->segment(2) == 'add') || ($this->uri->segment(1) == 'projects' && $this->uri->segment(2) == 'add') || ($this->uri->segment(1) == 'users' && ($this->uri->segment(2) == 'register' || $this->uri->segment(2) == 'login' || $this->uri->segment(2) == 'rpx'))) {
?>
		<!-- BBCode support -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/ed.js"></script>

		<!-- Resizing textarea -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/jquery.textarearesizer.compressed.js"></script>

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

<?php if ($this->uri->segment(1) == 'updates' && $this->uri->segment(2) == 'add') { ?>
		<!-- More file fields -->
        <script type="text/javascript" src="<?php echo url::base(); ?>js/browse.js"></script>
		<script type="text/javascript">
			function doOverlay() {
				document.getElementById('overlay').style.display = "table";
				document.getElementById('submit').value = "Submitting ... please wait.";
				document.getElementById('overlay').disabled = true;
			}
		</script>
<?php } ?>


<?php } ?>
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="<?php echo url::base(); ?>images/favicon.png" />
        <title>WIPUP: Document and share your works-in-progresses</title>

		<!-- Web fonts -->
		<link href='http://fonts.googleapis.com/css?family=Signika:600' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>

		<!-- Meta tags -->
		<link rev="made" href="mailto:dion@thinkmoult.com">
		<meta name="keywords" content="wipup wip document share progress">
		<meta name="description" content="WIPUP is a free way you can document, share and show us what you're working on.">
		<meta name="author" content="Dion Moult">

		<!-- Google Analytics -->
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-690493-3']);
		  _gaq.push(['_setDomainName', '.wipup.org']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
    </head>

	<body>

<?php $notification = $this->session->get_once('notification'); if (!empty($notification)) { ?>
		<script type="text/javascript">
		$(document).ready(function() {
			$("html").css("backgroundPosition", "0px 41px");
			$("#notifyclose").click(function(){
				$("#notification").css("display", "none");
				$("html").css("backgroundPosition", "0px 0px");
			});
		});
		</script>

		<div id="notification"><?php echo $notification; ?><img src="<?php echo url::base(); ?>images/fancy_close.png" class="icon" id="notifyclose" alt="close" /></div>
<?php } ?>

		<div id="container">
			<h1>
				<a href="<?php echo url::base(); ?>"><img src="<?php echo url::base(); ?>images/wipup.png" width="165" height="55" alt="WIPUP" /></a>
			</h1>

			<ul id="text-navigation" <?php if ($this->logged_in == TRUE) { ?>style="width: 525px"<?php } ?>>
				<li><div style="font-size: 10px;">
				<form action="<?php echo url::base(); ?>site/search/" method="post">
				<input type="hidden" value="updates" name="search" />
				<input type="text" name="keywords" id="search" value="Search..." onclick="this.value='';" /></li>
				</form>
				<span style="color: #777;">or</span> <a href="<?php echo url::base(); ?>updates/random/">Discover<img src="<?php echo url::base(); ?>images/random.png" class="icon" title="Go to random WIP" alt="Go to random WIP" /></a>

<span style="float: right;">
<?php if ($this->logged_in == TRUE) { ?><a href="<?php echo url::base(); ?>users/logout/">Logout <img src="<?php echo url::base(); ?>images/icons/logout.png" alt="Logout" title="Logout" class="icon" /></a><?php } ?>
</span>
				</div></li>
			</ul>

			<ul id="icon-navigation">
			<?php if ($this->logged_in == TRUE) { ?><li><img src="<?php echo url::base(); ?>images/navspace1.png" width="14" height="55" /></li><li><a href="<?php echo url::base(); ?>dashboard/"><img src="<?php echo url::base(); ?>images/dashboard.png" width="44" height="55" alt="WIP Stats" title="WIP Stats" /></a></li><li><img src="<?php echo url::base(); ?>images/navspace2.png" width="5" height="55" /></li><?php } else { ?><li><img src="<?php echo url::base(); ?>images/navspace1.png" width="14" height="55" /></li><?php } ?><li><a href="<?php echo url::base(); ?>updates/add/"><img src="<?php echo url::base(); ?>images/update.png" width="44" height="55" alt="Add WIP" title="Add WIP" /></a></li><li><img src="<?php echo url::base(); ?>images/navspace2.png" width="5" height="55" /></li><li><a href="<?php if ($this->logged_in == TRUE) { echo url::base() .'profiles/view/'. $this->username; } else { echo url::base() .'users/login/'; } ?>"><img src="<?php echo url::base(); ?>images/profile.png" width="44" height="55" alt="My WIPs" title="My WIPs" /></a></li><li><img src="<?php echo url::base(); ?>images/navspace3.png" width="18" height="55" /></li>
			</ul>
		</div>

		<div id="content-top">
			<div id="global-message"><?php if ($this->logged_in == TRUE) { ?>Hey <?php echo $this->username; ?>, <?php
			$ambitious_message = array(
				'be ambitious',
				'create something amazing',
				'be proud of what you do',
				'be happy',
				'share the love',
				'invent something',
				'change the world',
				'spread your knowledge',
				'create to help, not hinder',
				'I forgot'
			);
		echo $ambitious_message[rand(0,count($ambitious_message)-1)];
?><?php } else { ?>Be ambitious<?php } ?>
<?php if ($this->uri->string() == 'site' && $this->logged_in == FALSE) { ?>
			<img style="float: right;" src="<?php echo url::base(); ?>images/try.png" alt="Try WIPUP now!" title="Try WIPUP now!" /></div>
<?php } else { ?>
			<img style="float: right;" src="<?php echo url::base(); ?>images/show.png" alt="Show us a WIP!" title="Show us a WIP!" /></div>
<?php } ?>
		</div>

		<div id="content">
			<div id="content_container">
				<!-- <CONTENT> -->
