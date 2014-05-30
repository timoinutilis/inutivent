<?php

/*
INIT
*/

$page_subtitle = NULL;
$page_private = NULL;

function init_page($subtitle, $private)
{
	global $page_subtitle, $page_private;
	$page_subtitle = strip_tags($subtitle);
	$page_private = $private;
}

function page_title()
{
	global $page_subtitle;
	if ($page_subtitle)
	{
		echo "Inutivent | ".$page_subtitle;
	}
	else
	{
		echo "Inutivent";
	}
}

function page_extra_headers()
{
	global $page_private;
	if ($page_private)
	{
		echo <<<END
		<meta name="robots" content="noindex,nofollow">

END;
	}
}

?>