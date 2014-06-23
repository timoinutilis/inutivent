<?php

function sorted_users_with_status($users, $status)
{
	$filtered = array();

	foreach ($users as $user)
	{
		if ($user->status == $status)
		{
			$filtered[] = $user;
		}
	}

	usort($filtered, "cmp_users");

	return $filtered;
}

function cmp_users($a, $b)
{
	return strcmp($a->status_changed, $b->status_changed);
}

function html_text($text)
{
	return str_replace("\n", "<br>\n", htmlentities($text, 0, "UTF-8"));
}

function sort_by_created(&$items)
{
	usort($items, "cmp_by_created");
}

function cmp_by_created($a, $b)
{
	return strcmp($a->created, $b->created);
}

function hour_of_datetime($datetime)
{
	$parts = explode(" ", $datetime);
	$parts_time = explode(":", $parts[1]);
	return intval($parts_time[0]).":".$parts_time[1];
}

function date_of_datetime($datetime)
{
	$parts = explode(" ", $datetime);
	$parts_date = explode("-", $parts[0]);
	return intval($parts_date[2])."/".intval($parts_date[1])."/".$parts_date[0];
}

function days_since($datetime)
{
	$parts = explode(" ", $datetime);

	$date = strtotime($parts[0]);
	$now = time();
	$diff = $now - $date;

	return floor($diff / (60 * 60 * 24));
}

function relative_day($datetime)
{
	$days = days_since($datetime);

	if ($days == 0)
	{
		return _('today');
	}
	else if ($days == 1)
	{
		return _('yesterday');
	}
	return date_of_datetime($datetime);
}

function relative_time($datetime)
{
	$days = days_since($datetime);

	if ($days == 0)
	{
		return hour_of_datetime($datetime);
	}

	if ($days == 1)
	{
		$date = _('yesterday');
	}
	else if ($days <= 7)
	{
		$date = sprintf( _('%d days ago'), $days);
	}
	else
	{
		$date = date_of_datetime($datetime);
	}

	return sprintf( _('%1$s at %2$s'), $date, hour_of_datetime($datetime));
}

function external_url($url)
{
	echo get_external_url($url);
}

function get_external_url($url)
{
	return "link.php?url=".urlencode($url);	
}

?>