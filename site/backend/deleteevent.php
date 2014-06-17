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
		else
		{
			// delete uploaded files
			$dir = dirname(__FILE__).'/../uploads/'.$event_id;
			$files_ok = TRUE;
			if (is_dir($dir))
			{
				$files = glob($dir.'/*'); // get all file names
				foreach($files as $file)
				{
					if(is_file($file))
					{
						unlink($file); // delete file
					}
				}
				if (!rmdir($dir))
				{
					$files_ok = FALSE;
					return_error(ERROR_FAILED_DELETE_FILES, "Could not delete all uploaded files");
				}
			}

			if ($files_ok)
			{
				// delete from data base
				if (event_delete_completely($con, $event_id))
				{
					$result = array('success' => TRUE);
					echo json_encode($result);
				}
				else
				{
					return_error(ERROR_MYSQL, "MySQL error: ".db_error());
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