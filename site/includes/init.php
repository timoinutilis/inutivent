<?php

/*
INIT
*/

require_once(dirname(__FILE__).'/config.php');

$page_subtitle = NULL;
$page_private = NULL;

// locale/gettext init
$page_locale = get_locale();
putenv("LANG={$page_locale}");
setlocale(LC_ALL, $page_locale);
bindtextdomain(TEXT_DOMAIN, 'locale');
bind_textdomain_codeset(TEXT_DOMAIN, 'UTF-8');
textdomain(TEXT_DOMAIN);

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
		echo "Gromf | ".$page_subtitle;
	}
	else
	{
		echo "Gromf";
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

function page_locale()
{
	global $page_locale;
	echo $page_locale;
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