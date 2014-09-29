<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

header('Content-type: application/json');

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id'])
	|| !isset($_FILES["file"])
	|| empty($_FILES["file"]["name"]) )
{
	return_error(ERROR_MISSING_PARAMETERS, "Missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];

	$save_result = save_cover_for_event($event_id, $_FILES["file"]);

	if (isset($save_result["filename"]))
	{
		$filename = $save_result["filename"];

		$con = connect_to_db();
		if ($con)
		{
			if (event_update($con, $event_id, NULL, NULL, NULL, $filename))
			{
				$result = array('filename' => $filename);
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
	else
	{
		// error from image saving
		echo json_encode($save_result);
	}
}

?>