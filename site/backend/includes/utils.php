<?php

define('ERROR_MYSQL', 'mysql_error');
define('ERROR_MISSING_PARAMETERS', 'missing_parameters');
define('ERROR_INVALID_PARAMETERS', 'invalid_parameters');
define('ERROR_NO_PERMISSION', 'no_permission');
define('ERROR_FAILED_DELETE_FILES', 'failed_delete_files');
define('ERROR_FAILED_UPLOAD_FILE', 'failed_upload_file');
define('ERROR_INVALID_FILE', 'invalid_file');
define('ERROR_NOT_FOUND', 'not_found');

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

function clean_string($string)
{
	return strip_tags(trim($string));
}

function clean_string_line($string)
{
	$rules = array("\r" => '', "\n" => '', "\t" => '');
	return strtr(strip_tags(trim($string)), $rules);
}

?>