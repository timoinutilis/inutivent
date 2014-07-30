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

	$con = connect_to_db();
	if ($con)
	{
		$event = event_get_if_not_too_old($con, $event_id);
		if ($event === FALSE)
		{
			return_error(ERROR_MYSQL, "MySQL error: ".db_error());
		}
		else if ($event === NULL)
		{
			return_error(ERROR_NOT_FOUND, "Event not found");
		}
		else
		{
			user_update_visited($con, $event_id, $user_id);
			
			$users = user_get_all($con, $event_id);
			if ($users === FALSE)
			{
				return_error(ERROR_MYSQL, "MySQL error: ".db_error());
			}
			else if (!isset($users[$user_id]))
			{
				return_error(ERROR_NOT_FOUND, "User not found");
			}
			else
			{
				$posts = post_get_all($con, $event_id);
				if ($posts === FALSE)
				{
					return_error(ERROR_MYSQL, "MySQL error: ".db_error());
				}
				else
				{
					$result = array('event' => $event, 'users' => $users, 'posts' => $posts);
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

?>