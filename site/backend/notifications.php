<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

header('Content-type: application/json');

if (empty($_REQUEST['events']))
{
	return_error(ERROR_MISSING_PARAMETERS, "Missing parameters");
}
else
{
	$events = $_REQUEST['events'];

	$con = connect_to_db();
	if ($con)
	{
		$event_infos = explode(',', $events);
		$changed_events = array();

		foreach ($event_infos as $event_info)
		{
			$parts = explode('|', $event_info);
			$event_id = $parts[0];
			$last_opened = $parts[1];

			$changed_events[] = $event_id;
		}

		$result = array('events' => $changed_events);
		echo json_encode($result);
	}
	else
	{
		return_error(ERROR_MYSQL, "MySQL error: ".db_error());
	}
}

?>