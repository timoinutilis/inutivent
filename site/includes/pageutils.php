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

?>