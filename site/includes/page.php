<?php

require_once(dirname(__FILE__).'/config.php');

function get_header()
{
	$url = SITE_URL;

	echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<title>Inutivent</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--link rel="stylesheet" type="text/css" media="all" href="style.css" /-->
<script type="text/javascript" src="functions.js"></script>
</head>

<body>
<h1><a href="{$url}">Inutivent</a></h1>

END;
}

function get_footer()
{
	echo <<<END

</body>

</html>
END;
}

?>