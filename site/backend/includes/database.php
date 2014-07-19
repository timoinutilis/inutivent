<?php

require_once(dirname(__FILE__).'/../../includes/config.php');

define('STATUS_UNKNOWN', 'U');
define('STATUS_ATTENDING', 'A');
define('STATUS_NOT_ATTENDING', 'N');
define('STATUS_MAYBE_ATTENDING', 'M');

define('POST_TYPE_TEXT', 'T');

define('HASH_EVENT', 'md5');
define('HASH_USER', 'crc32');

$current_con = NULL;

function connect_to_db()
{
	global $current_con;
	$current_con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno())
	{
		return FALSE;
	}

	return $current_con;
}

function db_error()
{
	global $current_con;
	if (mysqli_connect_errno())
	{
		return mysqli_connect_error();
	}
	return mysqli_error($current_con);
}

function event_get_new_id($con)
{
	$time = microtime();
	$time_hash = hash(HASH_EVENT, $time);

	for ($i = 0; $i < 10; $i++)
	{
		$result = mysqli_query($con, "SELECT id FROM events WHERE id = '{$time_hash}'");
		if ($result === FALSE)
		{
			return FALSE;
		}
		if (mysqli_num_rows($result) == 0)
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
	$title = mysqli_real_escape_string($con, $title);
	$details = mysqli_real_escape_string($con, $details);
	$time = mysqli_real_escape_string($con, $time);
	$locale = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mysqli_real_escape_string($con, $_SERVER['HTTP_ACCEPT_LANGUAGE']) : 'unknown';

	$id = event_get_new_id($con);
	if ($id)
	{
		$result = mysqli_query($con, "INSERT INTO events (id, title, details, time, created, locale) VALUES ('{$id}', '{$title}', '{$details}', '{$time}', NOW(), '{$locale}')");
		if ($result)
		{
			return $id;
		}
	}
	return FALSE;
}

function event_update($con, $event_id, $title = NULL, $time = NULL, $details = NULL, $cover = NULL)
{
	$event_id = mysqli_real_escape_string($con, $event_id);

	$changes = array();

	if (!empty($title))
	{
		$title = mysqli_real_escape_string($con, $title);
		$changes[] = "title = '{$title}'";
	}
	if (!empty($time))
	{
		$time = mysqli_real_escape_string($con, $time);
		$changes[] = "time = '{$time}'";
	}
	if (!empty($details))
	{
		$details = mysqli_real_escape_string($con, $details);
		$changes[] = "details = '{$details}'";
	}
	if (!empty($cover))
	{
		$cover = mysqli_real_escape_string($con, $cover);
		$changes[] = "cover = '{$cover}'";
	}

	if (count($changes) > 0)
	{
		$changes_sql = implode(', ', $changes);

		$result = mysqli_query($con, "UPDATE events SET {$changes_sql} WHERE id = '{$event_id}'");
		return $result;
	}
	// no changes no error
	return TRUE;
}

function event_set_owner($con, $event_id, $user_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);
	$user_id = mysqli_real_escape_string($con, $user_id);

	$result = mysqli_query($con, "UPDATE events SET owner = '{$user_id}' WHERE id = '{$event_id}'");
	return $result;
}

function event_get($con, $event_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);

	$result = mysqli_query($con, "SELECT * FROM events WHERE id = '{$event_id}'");
	if ($result)
	{
		if (mysqli_num_rows($result) == 1)
		{
			return mysqli_fetch_object($result);
		}
		else
		{
			return NULL;
		}
	}
	else
	{
		return FALSE;
	}
}

function event_delete_completely($con, $event_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);

	// posts
	$result = mysqli_query($con, "DELETE FROM posts WHERE event_id = '{$event_id}'");
	if ($result)
	{
		// users
		$result = mysqli_query($con, "DELETE FROM users WHERE event_id = '{$event_id}'");
		if ($result)
		{
			// event
			$result = mysqli_query($con, "DELETE FROM events WHERE id = '{$event_id}'");
			return $result;
		}
	}
	return FALSE;
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
		$result = mysqli_query($con, "SELECT id FROM users WHERE event_id = '{$event_id}' AND id = '{$time_hash}'");
		if ($result === FALSE)
		{
			return FALSE;
		}
		if (mysqli_num_rows($result) == 0)
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
	$event_id = mysqli_real_escape_string($con, $event_id);
	$name = mysqli_real_escape_string($con, $name);
	$status = mysqli_real_escape_string($con, $status);

	$id = user_get_new_id($con, $event_id, $user_id_spare_obj);
	if ($id)
	{
		$result = mysqli_query($con, "INSERT INTO users (id, event_id, name, status, status_changed, visited, created) VALUES ('{$id}', '{$event_id}', '{$name}', '{$status}', NOW(), '0000-00-00 00:00:00', NOW())");
		if ($result)
		{
			return $id;
		}
	}
	return FALSE;
}

function user_update($con, $event_id, $user_id, $status, $name)
{
	$event_id = mysqli_real_escape_string($con, $event_id);
	$user_id = mysqli_real_escape_string($con, $user_id);

	$changes = array();

	if (!empty($status))
	{
		$status = mysqli_real_escape_string($con, $status);
		$changes[] = "status = '{$status}'";
		$changes[] = "status_changed = NOW()";
	}
	if (!empty($name))
	{
		$name = mysqli_real_escape_string($con, $name);
		$changes[] = "name = '{$name}'";
	}

	if (count($changes) > 0)
	{
		$changes_sql = implode(', ', $changes);

		$result = mysqli_query($con, "UPDATE users SET {$changes_sql} WHERE event_id = '{$event_id}' AND id = '{$user_id}'");
		return $result;
	}
	// no changes no error
	return TRUE;
}

function user_update_visited($con, $event_id, $user_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);
	$user_id = mysqli_real_escape_string($con, $user_id);

	$result = mysqli_query($con, "UPDATE users SET visited = NOW() WHERE event_id = '{$event_id}' AND id = '{$user_id}'");
	return $result;
}

function user_get($con, $event_id, $user_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);
	$user_id = mysqli_real_escape_string($con, $user_id);

	$result = mysqli_query($con, "SELECT * FROM users WHERE id = '{$user_id}' AND event_id = '{$event_id}'");
	if ($result)
	{
		if (mysqli_num_rows($result) == 1)
		{
			return mysqli_fetch_object($result);
		}
		else
		{
			return NULL;
		}
	}
	return FALSE;
}

function user_get_all($con, $event_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);

	$result = mysqli_query($con, "SELECT * FROM users WHERE event_id = '{$event_id}'");
	if ($result)
	{
		$users = array();
		while ($user = mysqli_fetch_object($result))
		{
			$users[$user->id] = $user;
		}
		return $users;
	}
	return FALSE;
}

function post_create($con, $event_id, $user_id, $type, $data)
{
	$event_id = mysqli_real_escape_string($con, $event_id);
	$user_id = mysqli_real_escape_string($con, $user_id);
	$type = mysqli_real_escape_string($con, $type);
	$data = mysqli_real_escape_string($con, $data);

	$result = mysqli_query($con, "INSERT INTO posts (event_id, user_id, type, data, created) VALUES ('{$event_id}', '{$user_id}', '{$type}', '{$data}', NOW())");
	if ($result)
	{
		return mysqli_insert_id($con);
	}
	return FALSE;
}

function post_get_all($con, $event_id)
{
	$event_id = mysqli_real_escape_string($con, $event_id);

	$result = mysqli_query($con, "SELECT * FROM posts WHERE event_id = '{$event_id}'");
	if ($result)
	{
		$posts = array();
		while ($post = mysqli_fetch_object($result))
		{
			$posts[] = $post;
		}
		return $posts;
	}
	return FALSE;
}
?>