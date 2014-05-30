<?php

/*
INVITE
*/

require_once(dirname(__FILE__).'/includes/config.php');

if (   empty($_REQUEST['event'])
	|| empty($_REQUEST['user']) )
{
	header("Location: ".SITE_URL);
	exit();
}
else
{
	$event_id = $_REQUEST['event'];
	$user_id = $_REQUEST['user'];
}

require_once(dirname(__FILE__).'/backend/includes/database.php');
require_once(dirname(__FILE__).'/includes/init.php');

init_page("Invitar", TRUE);

include 'includes/header.php';

$all_loaded = FALSE;
$error = "";

$con = connect_to_db();
if (!$con)
{
	$error = "connect_to_db";
}
else
{

	$event = event_get($con, $event_id);
	if ($event === FALSE)
	{
		$error = "event";
	}
	else
	{
		// ready
		$all_loaded = TRUE;
	}

}

if ($all_loaded)
{
	include 'includes/content-invite.php';
}
else if ($error == "event")
{
	include 'includes/content-not-found.php';
}
else
{
	include 'includes/content-error.php';
}

include 'includes/footer.php';


// functions

function header_image_url()
{
	global $event_id, $event;
	if (!empty($event->cover))
	{
		echo "uploads/".$event_id."/".$event->cover;
	}
	else
	{
		echo "images/default_header.jpg";
	}
}

function event_title()
{
	global $event;
	echo $event->title;
}

function event_url()
{
	global $event_id, $user_id;
	echo "event.php?event={$event_id}&user={$user_id}";;
}

?>