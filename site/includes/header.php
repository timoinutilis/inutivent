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
		<meta name = "viewport" content = "width = 480, user-scalable = no">
		<link rel="stylesheet" type="text/css" media="all" href="style.css">
		<script type="text/javascript" src="functions.js"></script>
<?php page_extra_headers(); ?>
	</head>

	<body>

		<div id="wrapper">

			<div id="header" class="section">
				<div class="logo">
					<h1><a href="<?php echo SITE_URL; ?>">Inutivent</a> <span style="color: #f00;">beta</span></h1>
				</div>
				<div class="options">
					<a href="create.php">+ Crear evento</a>
				</div>
			</div>

			<div id="content">
