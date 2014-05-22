<?php

require_once(dirname(__FILE__).'/../../includes/config.php');

function connect_to_db()
{
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if ($con)
	{
		if (!mysql_select_db(DB_NAME, $con))
		{
			return FALSE;
		}
	}
	return $con;
}

function event_get_new_id($con)
{
	$time = microtime();
	$time_hash = hash('crc32', $time);

	for ($i = 0; $i < 10; $i++)
	{
		$result = mysql_query("SELECT id FROM events WHERE id = '{$time_hash}'", $con);
		if ($result === FALSE)
		{
			return FALSE;
		}
		if (mysql_num_rows($result) == 0)
		{
			return $time_hash;
		}
		$time++;
		$time_hash = hash('crc32', $time);
	}

	return FALSE;
}

function event_create($con, $title, $details, $time)
{
	$title = mysql_escape_string($title);
	$details = mysql_escape_string($details);

	$id = event_get_new_id($con);
	if ($id)
	{
		$result = mysql_query("INSERT INTO events (id, title, details, time) VALUES ('{$id}', '{$title}', '{$details}', '{$time}')", $con);
		if ($result)
		{
			return $id;
		}
	}
	return FALSE;
}

function event_set_owner($con, $event_id, $user_id)
{
	$result = mysql_query("UPDATE events SET owner = '{$user_id}' WHERE id = '{$event_id}'", $con);
	return $result;
}

function event_get($con, $event_id)
{
	$result = mysql_query("SELECT * FROM events WHERE id = '{$event_id}'", $con);
	if ($result && mysql_num_rows($result) == 1)
	{
		return mysql_fetch_object($result);
	}
	else
	{
		return FALSE;
	}
}

function user_get_new_id($con, $event_id)
{
	$time = microtime();
	$time_hash = hash('crc32', $time);

	for ($i = 0; $i < 10; $i++)
	{
		$result = mysql_query("SELECT id FROM users WHERE event_id = '{$event_id}' AND id = '{$time_hash}'", $con);
		if ($result === FALSE)
		{
			return FALSE;
		}
		if (mysql_num_rows($result) == 0)
		{
			return $time_hash;
		}
		$time++;
		$time_hash = hash('crc32', $time);
	}

	return FALSE;
}

function user_create($con, $event_id, $name)
{
	$name = mysql_escape_string($name);
	$id = user_get_new_id($con, $event_id);
	if ($id)
	{
		$result = mysql_query("INSERT INTO users (id, event_id, name) VALUES ('{$id}', '{$event_id}', '{$name}')", $con);
		if ($result)
		{
			return $id;
		}
	}
	return FALSE;
}

function user_get($con, $event_id, $user_id)
{
	$result = mysql_query("SELECT * FROM users WHERE id = '{$user_id}' AND event_id = '{$event_id}'", $con);
	if ($result && mysql_num_rows($result) == 1)
	{
		return mysql_fetch_object($result);
	}
	else
	{
		return FALSE;
	}
}

?>