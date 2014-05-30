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