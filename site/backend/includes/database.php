<?php

require_once(dirname(__FILE__).'/../../includes/config.php');

define('STATUS_UNKNOWN', 'U');
define('STATUS_ATTENDING', 'A');
define('STATUS_NOT_ATTENDING', 'N');
define('STATUS_MAYBE_ATTENDING', 'M');

define('POST_TYPE_TEXT', 'T');

define('HASH_EVENT', 'md5');
define('HASH_USER', 'crc32');

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
	$time_hash = hash(HASH_EVENT, $time);

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
		$time_hash = hash(HASH_EVENT, $time);
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
		$result = mysql_query("INSERT INTO events (id, title, details, time, created) VALUES ('{$id}', '{$title}', '{$details}', '{$time}', NOW())", $con);
		if ($result)
		{
			return $id;
		}
	}
	return FALSE;
}

function event_update($con, $event_id, $title = NULL, $time = NULL, $details = NULL, $cover = NULL)
{
	$changes = array();

	if (!empty($title))
	{
		$title = mysql_escape_string($title);
		$changes[] = "title = '{$title}'";
	}
	if (!empty($time))
	{
		$time = mysql_escape_string($time);
		$changes[] = "time = '{$time}'";
	}
	if (!empty($details))
	{
		$details = mysql_escape_string($details);
		$changes[] = "details = '{$details}'";
	}
	if (!empty($cover))
	{
		$cover = mysql_escape_string($cover);
		$changes[] = "cover = '{$cover}'";
	}

	if (count($changes) > 0)
	{
		$changes_sql = implode(', ', $changes);

		$result = mysql_query("UPDATE events SET {$changes_sql} WHERE id = '{$event_id}'", $con);
		return $result;
	}
	// no changes no error
	return TRUE;
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

function user_get_new_id($con, $event_id, $spare_obj = NULL)
{
	if ($spare_obj && isset($spare_obj->last_time))
	{
		$time = $spare_obj->last_time + mt_rand(1, 60000);
	}
	else
	{
		$time = microtime();
	}
	$time_hash = hash(HASH_USER, $time);

	for ($i = 0; $i < 10; $i++)
	{
		$result = mysql_query("SELECT id FROM users WHERE event_id = '{$event_id}' AND id = '{$time_hash}'", $con);
		if ($result === FALSE)
		{
			return FALSE;
		}
		if (mysql_num_rows($result) == 0)
		{
			if ($spare_obj)
			{
				$spare_obj->last_time = $time;
			}
			return $time_hash;
		}
		$time++;
		$time_hash = hash(HASH_USER, $time);
	}

	return FALSE;
}

function user_create($con, $event_id, $name, $status = STATUS_UNKNOWN, $user_id_spare_obj = NULL)
{
	$name = mysql_escape_string($name);
	$id = user_get_new_id($con, $event_id, $user_id_spare_obj);
	if ($id)
	{
		$result = mysql_query("INSERT INTO users (id, event_id, name, status, status_changed, visited) VALUES ('{$id}', '{$event_id}', '{$name}', '{$status}', NOW(), NOW())", $con);
		if ($result)
		{
			return $id;
		}
	}
	return FALSE;
}

function user_update($con, $event_id, $user_id, $status, $name)
{
	$changes = array();

	if (!empty($status))
	{
		$changes[] = "status = '{$status}'";
		$changes[] = "status_changed = NOW()";
	}
	if (!empty($name))
	{
		$changes[] = "name = '{$name}'";
	}

	if (count($changes) > 0)
	{
		$changes_sql = implode(', ', $changes);

		$result = mysql_query("UPDATE users SET {$changes_sql} WHERE event_id = '{$event_id}' AND id = '{$user_id}'", $con);
		return $result;
	}
	// no changes no error
	return TRUE;
}

function user_get($con, $event_id, $user_id)
{
	$result = mysql_query("SELECT * FROM users WHERE id = '{$user_id}' AND event_id = '{$event_id}'", $con);
	if ($result && mysql_num_rows($result) == 1)
	{
		return mysql_fetch_object($result);
	}
	return FALSE;
}

function user_get_all($con, $event_id)
{
	$result = mysql_query("SELECT * FROM users WHERE event_id = '{$event_id}'", $con);
	if ($result)
	{
		$users = array();
		while ($user = mysql_fetch_object($result))
		{
			$users[$user->id] = $user;
		}
		return $users;
	}
	return FALSE;
}

function post_create($con, $event_id, $user_id, $type, $data)
{
	$data = mysql_escape_string($data);
	$result = mysql_query("INSERT INTO posts (event_id, user_id, type, data, created) VALUES ('{$event_id}', '{$user_id}', '{$type}', '{$data}', NOW())", $con);
	if ($result)
	{
		return mysql_insert_id();
	}
	return FALSE;
}

function post_get_all($con, $event_id, $user_id)
{
	$result = mysql_query("SELECT * FROM posts WHERE event_id = '{$event_id}'", $con);
	if ($result)
	{
		$posts = array();
		while ($post = mysql_fetch_object($result))
		{
			$posts[] = $post;
		}
		return $posts;
	}
	return FALSE;
}
?>