<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id'])
	|| empty($_REQUEST['type'])
	|| empty($_REQUEST['data']) )
{
	return_error("missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];
	$type = $_REQUEST['type'];
	$data = $_REQUEST['data'];

	$con = connect_to_db();
	if ($con)
	{
		$post_id = post_create($con, $event_id, $user_id, $type, $data);
		if ($post_id === FALSE)
		{
			return_error("MySQL error: ".mysql_error());
		}
		else
		{
			$result = array('post_id' => $post_id);
			echo json_encode($result);
		}
	}
	else
	{
		return_error("MySQL error: ".mysql_error());
	}
}

?>