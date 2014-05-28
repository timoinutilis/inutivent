<?php

function return_error($message)
{
	$error = array('error' => $message);
	echo json_encode($error);
}

function convert_to_datetime($date, $hour)
{
	$datetime_obj = DateTime::createFromFormat("j/n/Y G:i", $date." ".$hour);
	return $datetime_obj->format("Y-m-d H:i:s");
}

?>