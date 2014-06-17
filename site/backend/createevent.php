<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');
require_once(dirname(__FILE__).'/includes/mail.php');

if (   empty($_REQUEST['name'])
	|| empty($_REQUEST['mail'])
	|| !isset($_REQUEST['title'])
	|| !isset($_REQUEST['date'])
	|| !isset($_REQUEST['hour'])
	|| !isset($_REQUEST['details']) )
{
	return_error(ERROR_MISSING_PARAMETERS, "Missing parameters");
}
else
{
	$name = clean_string_line($_REQUEST['name']);
	$mail = $_REQUEST['mail'];
	$title = clean_string_line($_REQUEST['title']);
	$date = $_REQUEST['date'];
	$hour = $_REQUEST['hour'];
	$details = clean_string($_REQUEST['details']);

	$time = convert_to_datetime($date, $hour);
	if ($time === FALSE)
	{
		return_error(ERROR_INVALID_PARAMETERS, "Wrong date or time format");
	}
	else if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
	{
		return_error(ERROR_INVALID_PARAMETERS, "Invalid e-mail");
	}
	else
	{
		$con = connect_to_db();
		if ($con)
		{
			$time = convert_to_datetime($date, $hour);
			$event_id = event_create($con, $title, $details, $time);
			if ($event_id === FALSE)
			{
				return_error(ERROR_MYSQL, "MySQL error: ".db_error());
			}
			else
			{
				$user_id = user_create($con, $event_id, $name, STATUS_ATTENDING);
				if ($user_id === FALSE)
				{
					return_error(ERROR_MYSQL, "MySQL error: ".db_error());
				}
				else
				{
					$success = event_set_owner($con, $event_id, $user_id);
					if ($success === FALSE)
					{
						return_error(ERROR_MYSQL, "MySQL error: ".db_error());
					}
					else
					{
						$event = new stdClass();
						$event->id = $event_id;
						$event->title = $title;
						
						$user = new stdClass();
						$user->id = $user_id;
						$user->name = $name;

						send_owner_mail($mail, $event, $user);
						$result = array('event_id' => $event_id, 'user_id' => $user_id);
						echo json_encode($result);
					}
				}
			}
		}
		else
		{
			return_error(ERROR_MYSQL, "MySQL error: ".db_error());
		}
	}
}
?>