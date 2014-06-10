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
	$status = !empty($_REQUEST['status']) ? $_REQUEST['status'] : NULL;
	$name = !empty($_REQUEST['name']) ? clean_string_line($_REQUEST['name']) : NULL;

	$con = connect_to_db();
	if ($con)
	{
		if (user_update($con, $event_id, $user_id, $status, $name))
		{
			$result = array('success' => TRUE);
			if (!empty($status))
			{
				$result['status'] = $status;
			}
			if (!empty($name))
			{
				$result['name'] = $name;
			}
			echo json_encode($result);
		}
		else
		{
			return_error("MySQL error: ".db_error());
		}
	}
	else
	{
		return_error("MySQL error: ".db_error());
	}
}

?>