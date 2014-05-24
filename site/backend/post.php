<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

if (   !isset($_REQUEST['event_id'])
	|| !isset($_REQUEST['user_id'])
	|| !isset($_REQUEST['type'])
	|| !isset($_REQUEST['data']) )
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
			return_error(mysql_error());
		}
		else
		{
			$result = array('post_id' => $post_id);
			echo json_encode($result);
		}
	}
	else
	{
		return_error(mysql_error());
	}
}

?>