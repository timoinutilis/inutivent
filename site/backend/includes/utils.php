<?php

define('ERROR_MYSQL', 'mysql_error');
define('ERROR_MISSING_PARAMETERS', 'missing_parameters');
define('ERROR_INVALID_PARAMETERS', 'invalid_parameters');
define('ERROR_NO_PERMISSION', 'no_permission');
define('ERROR_FAILED_DELETE_FILES', 'failed_delete_files');
define('ERROR_FAILED_UPLOAD_FILE', 'failed_upload_file');
define('ERROR_INVALID_FILE', 'invalid_file');
define('ERROR_NOT_FOUND', 'not_found');

require_once(dirname(__FILE__).'/../../includes/config.php');
require_once(dirname(__FILE__).'/database.php');
require_once(dirname(__FILE__).'/files.php');

function return_error($error_id, $message)
{
	$error = array('error_id' => $error_id, 'error' => $message);
	echo json_encode($error);
}

function convert_to_datetime($date, $hour)
{
	$datetime_obj = DateTime::createFromFormat("j/n/Y G:i", $date." ".$hour);
	if (!$datetime_obj)
	{
		return FALSE;
	}
	return $datetime_obj->format("Y-m-d H:i:s");
}

function is_datetime_allowed($datetime)
{
	$now = new DateTime();
	$event_time = DateTime::createFromFormat("Y-m-d H:i:s", $datetime);
	$diff = $event_time->diff($now);
	if ($diff->invert == 0)
	{
		// is in past
		return FALSE;
	}
	else if ($diff->days > 365)
	{
		// too far in future
		return FALSE;
	}
	return TRUE;
}

function clean_string($string)
{
	return strip_tags(trim($string));
}

function clean_string_line($string)
{
	$rules = array("\r" => '', "\n" => '', "\t" => '');
	return strtr(strip_tags(trim($string)), $rules);
}

function event_get_if_not_too_old($con, $event_id)
{
	$event_object = event_get($con, $event_id);

	if ($event_object && event_is_old($event_object))
	{
		// delete old event
		delete_files_of_event($event_id);
		event_delete_completely($con, $event_id);
		return NULL;
	}
	return $event_object;
}

function event_is_old($event_object)
{
	$now = new DateTime();
	$event_time = DateTime::createFromFormat('Y-m-d G:i:s', $event_object->time);
	$diff = $event_time->diff($now);
	$is_old = ($diff->invert == 0) && ($diff->days > DAYS_TO_DELETE);
	return $is_old;
}

?>