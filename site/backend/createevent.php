<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');
require_once(dirname(__FILE__).'/includes/mail.php');

if (   !isset($_REQUEST['title'])
	|| !isset($_REQUEST['name'])
	|| !isset($_REQUEST['mail'])
	|| !isset($_REQUEST['time'])
	|| !isset($_REQUEST['details']) )
{
	return_error("missing parameters");
}
else
{
	$title = $_REQUEST['title'];
	$name = $_REQUEST['name'];
	$mail = $_REQUEST['mail'];
	$time = $_REQUEST['time'];
	$details = $_REQUEST['details'];

	$con = connect_to_db();
	if ($con)
	{
		$event_id = event_create($con, $title, $details, $time);
		if ($event_id === FALSE)
		{
			return_error(mysql_error());
		}
		else
		{
			$user_id = user_create($con, $event_id, $name, STATUS_ATTENDING);
			if ($user_id === FALSE)
			{
				return_error(mysql_error());
			}
			else
			{
				$success = event_set_owner($con, $event_id, $user_id);
				if ($success === FALSE)
				{
					return_error(mysql_error());
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
		return_error(mysql_error());
	}
}
?>