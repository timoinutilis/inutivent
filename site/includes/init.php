<?php

/*
INIT
*/

$page_subtitle = NULL;
$page_private = NULL;

// locale/gettext init
$locale = get_locale();
$domain = "inutivent";
putenv("LANG={$locale}");
setlocale(LC_ALL, $locale);
bindtextdomain($domain, 'locale');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

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

function get_locale()
{
	$arr = array(
	    'de_DE',
	    'es_ES',
	    'en_US'
	);
	if (isset($_GET['lang']))
	{
		$language = $_GET['lang'];
	}
	else
	{
		$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
	}
	return locale_lookup($arr, $language, FALSE, 'en_US');
}

?>