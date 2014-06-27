<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id']) )
{
	return_error(ERROR_MISSING_PARAMETERS, "Missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];
	$title = !empty($_REQUEST['title']) ? clean_string_line($_REQUEST['title']) : NULL;
	$date = !empty($_REQUEST['date']) ? $_REQUEST['date'] : NULL;
	$hour = !empty($_REQUEST['hour']) ? $_REQUEST['hour'] : NULL;
	$details = !empty($_REQUEST['details']) ? clean_string($_REQUEST['details']) : NULL;

	$time = ($date && $hour) ? convert_to_datetime($date, $hour) : NULL;

	if ($time === FALSE)
	{
		return_error(ERROR_INVALID_PARAMETERS, "Wrong date or time format");
	}
	else if ($time && !is_datetime_allowed($time))
	{
		return_error(ERROR_INVALID_PARAMETERS, "Date cannot be in the past or too far in the future");
	}
	else
	{
		$con = connect_to_db();
		if ($con)
		{
			$event = event_get($con, $event_id);
			if ($event === FALSE)
			{
				return_error(ERROR_MYSQL, "MySQL error: ".db_error());
			}
			else if ($event === NULL)
			{
				return_error(ERROR_NOT_FOUND, "Event not found");
			}
			else if ($event->owner != $user_id)
			{
				return_error(ERROR_NO_PERMISSION, "No permission");
			}
			else if (event_update($con, $event_id, $title, $time, $details))
			{
				$result = array('success' => TRUE);
				echo json_encode($result);
			}
			else
			{
				return_error(ERROR_MYSQL, "MySQL error: ".db_error());
			}
		}
		else
		{
			return_error(ERROR_MYSQL, "MySQL error: ".db_error());
		}
	}
}

?>