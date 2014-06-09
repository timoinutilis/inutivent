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

	$con = connect_to_db();
	if ($con)
	{
		$event = event_get($con, $event_id);
		if ($event === FALSE)
		{
			return_error("MySQL error: ".db_error());
		}
		else
		{
			$users = user_get_all($con, $event_id);
			if ($users === FALSE || !isset($users[$user_id]))
			{
				return_error("MySQL error: ".db_error());
			}
			else
			{
				$posts = post_get_all($con, $event_id);
				if ($posts === FALSE)
				{
					return_error("MySQL error: ".db_error());
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
		return_error("MySQL error: ".db_error());
	}
}

?>