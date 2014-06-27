<?php

/*
HEADER
*/

require_once(dirname(__FILE__).'/init.php');

?><!DOCTYPE HTML>
<html>

	<head>
		<title><?php page_title(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name = "viewport" content = "width = device-width">
		<link rel="stylesheet" type="text/css" media="all" href="style.css">
		<script type="text/javascript" src="functions.js"></script>
<?php page_extra_headers(); ?>
	</head>

	<body>

		<div id="wrapper">

			<div id="header" class="section">
				<div class="logo">
					<h1><a href="<?php echo SITE_URL; ?>"><img src="images/header_logo.png">Gromf</a> <span style="color:#c00; font-size:75%;">BETA</span></h1>
				</div>
				<div class="options">
					<a href="create.php"><?php echo _('+ Create Event'); ?></a>
				</div>
			</div>

			<div id="content">
