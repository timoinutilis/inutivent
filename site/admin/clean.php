<?php

/*
CLEAN - DELETE OLD EVENTS
*/

require_once(dirname(__FILE__).'/../includes/config.php');
require_once(dirname(__FILE__).'/../backend/includes/database.php');
require_once(dirname(__FILE__).'/../backend/includes/files.php');

?><!DOCTYPE HTML>
<html>
	<head>
		<title>Clean</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<h1>Clean</h1>
<?php

$con = connect_to_db();
if (!$con)
{
	echo "Error connecting to database: ".db_error()."<br>\n";
}
else
{
	$result = mysqli_query($con, "SELECT id FROM events WHERE DATEDIFF(CURDATE(),time) > ".DAYS_TO_DELETE);
	if ($result)
	{
		$events = array();
		while ($event_object = mysqli_fetch_object($result))
		{
			$events[] = $event_object->id;
		}

		if (count($events) == 0)
		{
			echo "Nothing to delete<br>\n";	
		}

		foreach ($events as $event_id)
		{
			echo "Event ID {$event_id}<br>\n";
			$files_ok = delete_files_of_event($event_id);
			if (!$files_ok)
			{
				echo "- Error deleting uploaded files<br>\n";
			}
			else
			{
				$db_ok = event_delete_completely($con, $event_id);
				if (!$db_ok)
				{
					echo "- Error deleting from database: ".db_error()."<br>\n";
				}
			}
		}
	}
	else
	{
		echo "Error query: ".db_error()."<br>";
	}
}


?>
	</body>
</html>
