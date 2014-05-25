<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id']) )
{
	return_error("missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];
	$title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : NULL;
	$time = !empty($_REQUEST['time']) ? $_REQUEST['time'] : NULL;
	$details = !empty($_REQUEST['details']) ? $_REQUEST['details'] : NULL;

	$con = connect_to_db();
	if ($con)
	{
		$event = event_get($con, $event_id);
		if ($event === FALSE)
		{
			return_error(mysql_error());
		}
		else if ($event->owner != $user_id)
		{
			return_error("no permission");
		}
		else if (event_update($con, $event_id, $title, $time, $details))
		{
			$result = array('success' => TRUE);
			echo json_encode($result);
		}
		else
		{
			return_error(mysql_error());
		}
	}
	else
	{
		return_error(mysql_error());
	}
}

?>