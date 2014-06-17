<?php

/*
EVENT
*/

require_once(dirname(__FILE__).'/includes/config.php');

if (   empty($_REQUEST['event'])
	|| empty($_REQUEST['user']) )
{
	header("Location: ".SITE_URL);
	exit();
}
else
{
	$event_id = $_REQUEST['event'];
	$user_id = $_REQUEST['user'];
}

require_once(dirname(__FILE__).'/includes/pageutils.php');
require_once(dirname(__FILE__).'/backend/includes/database.php');
require_once(dirname(__FILE__).'/includes/init.php');

$all_loaded = FALSE;
$error = "";

$con = connect_to_db();
if (!$con)
{
	$error = "connect_to_db";
}
else
{
	$event = event_get($con, $event_id);
	if ($event === FALSE || $event === NULL)
	{
		$error = "event";
	}
	else
	{
		$user = user_get($con, $event_id, $user_id);
		if ($user === FALSE || $user === NULL)
		{
			$error = "user";
		}
		else
		{
			user_update_visited($con, $event_id, $user_id);

			$users = user_get_all($con, $event_id);
			if ($users === FALSE)
			{
				$error = "users";
			}
			else
			{
				$posts = post_get_all($con, $event_id);
				if ($posts === FALSE)
				{
					$error = "posts";
				}
				else
				{
					sort_by_created($posts);

					// ready
					$all_loaded = TRUE;
				}
			}
		}
	}
}

init_page($all_loaded ? $event->title : NULL, TRUE);

include 'includes/header.php';

if ($all_loaded)
{
	$is_owner = ($event->owner == $user_id);
	include 'includes/content-event.php';
}
else if ($error == "event" || $error == "user")
{
	include 'includes/content-not-found.php';
}
else
{
	include 'includes/content-error.php';
}

include 'includes/footer.php';


// functions

function header_image_url()
{
	global $event_id, $event;
	if (!empty($event->cover))
	{
		echo "uploads/".$event_id."/".$event->cover;
	}
	else
	{
		echo "images/default_header.jpg";
	}
}

function event_title()
{
	global $event;
	echo html_text($event->title);
}

function event_date()
{
	global $event;
	echo date_of_datetime($event->time);
}

function event_hour()
{
	global $event;
	echo hour_of_datetime($event->time);
}

function event_owner_name()
{
	global $event, $users;
	echo html_text($users[$event->owner]->name);
}

function event_details()
{
	global $event;
	echo html_text($event->details);
}

function user_name()
{
	global $user;
	echo html_text($user->name);
}

function status_button($status)
{
	global $user;
	
	if ($status == STATUS_ATTENDING)
	{
		$css_class = "yes";
	}
	else if ($status == STATUS_NOT_ATTENDING)
	{
		$css_class = "no";
	}
	else
	{
		$css_class = "maybe";
	}

	$attr = "";
	if ($user->status != STATUS_UNKNOWN)
	{
		if ($user->status == $status)
		{
			$css_class .= " selected";
			$attr = " disabled";
		}
		else
		{
			$css_class .= " unselected";
		}
	}

	$attr .= " onclick=\"submitAssist('{$status}')\"";

	echo "class=\"{$css_class}\"{$attr}";
}

function invite_url()
{
	global $event_id, $user_id;
	echo "invite.php?event={$event_id}&user={$user_id}";
}

function guest_list()
{
	guests_list_for_status('asistirán', STATUS_ATTENDING);
	guests_list_for_status('tal vez asistan', STATUS_MAYBE_ATTENDING);
	guests_list_for_status('no asistirán', STATUS_NOT_ATTENDING);
	guests_list_for_status('invitados', STATUS_UNKNOWN);
}

function guests_list_for_status($title, $status)
{
	global $users, $event;
	$guests = sorted_users_with_status($users, $status);
	$has_unvisited_users = FALSE;
	if (count($guests) > 0)
	{
		echo <<<END
							<h2>{$title}</h2>
							<ul>

END;
		foreach ($guests as $guest)
		{
			$guest_name = html_text($guest->name);
			$extras = "";
			if ($guest->id == $event->owner)
			{
				$extras .= " (Org.)";
			}
			if ($guest->visited == '0000-00-00 00:00:00')
			{
				// hasn't seen the site yet
				$extras .= " *";
				$has_unvisited_users = TRUE;
			}
			echo <<<END
								<li>{$guest_name}{$extras}</li>

END;
		}
		echo <<<END
							</ul>

END;
		if ($has_unvisited_users)
		{
		echo <<<END
							<p class="note">* aún no lo ha visto</p>

END;
		}
	}
}

function posts()
{
	global $posts, $users;
	foreach ($posts as $post)
	{
		$name = html_text($users[$post->user_id]->name);
		$text = html_text($post->data);
		$time = relative_time($post->created);
		echo <<<END
								<div class="post">
									<span class="name">{$name}:</span>
									<span name="post-text" class="text">{$text}</span>
									<p class="time">{$time}</p>
								</div>

END;
	}
}
?>