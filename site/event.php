<?php

require_once(dirname(__FILE__).'/includes/page.php');
require_once(dirname(__FILE__).'/includes/pageutils.php');
require_once(dirname(__FILE__).'/backend/includes/database.php');

$event_id = $_REQUEST['event'];
$user_id = $_REQUEST['user'];



get_header();

$con = connect_to_db();
if ($con)
{
	$event = event_get($con, $event_id);
	if ($event === FALSE)
	{
		echo '<p>Evento no encontrado! Quizá ya está borrado.</p>';
	}
	else
	{
		$is_owner = ($event->owner == $user_id);

		$user = user_get($con, $event_id, $user_id);

		if ($user === FALSE)
		{
			echo '<p>Usuario no encontrado!</p>';
		}
		else
		{

?>

<h2><?php echo $event->title; ?></h2>

<p>
<form action="backend/updateuser.php" method="POST" id="form-assist" onsubmit="return false">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="radio" name="status" value="A" <?php if ($user->status == STATUS_ATTENDING) echo 'checked' ?> onchange="submitAssist();">Asistir<br>
<input type="radio" name="status" value="M" <?php if ($user->status == STATUS_MAYBE_ATTENDING) echo 'checked' ?> onchange="submitAssist();">Tal vez asistir<br>
<input type="radio" name="status" value="N" <?php if ($user->status == STATUS_NOT_ATTENDING) echo 'checked' ?> onchange="submitAssist();">No asistir<br>
</form>
</p>

<p>
<form action="backend/updateuser.php" method="POST" onsubmit="return onNameSubmit(event)">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
Tu nombre: <input type="text" name="name" value="<?php echo $user->name; ?>">
<input type="submit" value="Cambiar">
</form>
</p>

<h3>Fecha</h3>
<p>
<?php echo $event->time; ?>
</p>
<h3>Detalles</h3>
<p>
<?php echo html_text($event->details); ?>
</p>

<?php

			$users = user_get_all($con, $event_id);
			if ($users === FALSE)
			{
				echo '<p>'.mysql_error().'</p>';
			}
			else
			{
				get_posts($con, $event_id, $user_id, $users);
			
				echo '<h3>Invitados</h3>';
				if ($is_owner)
				{
					$invite_link = "invite.php?event={$event_id}&user={$user_id}";
					echo "<p><a href=\"{$invite_link}\">Invitar</a></p>";
				}
				get_guests_list('asistirán', $users, STATUS_ATTENDING, $event);
				get_guests_list('tal vez asistan', $users, STATUS_MAYBE_ATTENDING, $event);
				get_guests_list('no asistirán', $users, STATUS_NOT_ATTENDING, $event);
				get_guests_list('invitados', $users, STATUS_UNKNOWN, $event);
			}
		}
	}
}
else
{
	echo mysql_error();
}

?>

<script>

function submitAssist()
{
	var form = document.getElementById('form-assist');
	sendForm(form, onComplete, onError);
}

function onNameSubmit(event)
{
	var form = event.target;
	sendForm(form, onComplete, onError);
	return false;
}

function onPostSubmit(event)
{
	var form = event.target;
	sendForm(form, onComplete, onError);
	return false;
}

function onComplete(data)
{
	window.location.reload();
}

function onError(error)
{
	alert(error);
}

</script>

<?php

get_footer();

function get_posts($con, $event_id, $user_id, $users)
{
?>

<h3>Publicaciones</h3>
<p>
<form action="backend/post.php" method="POST" onsubmit="return onPostSubmit(event)">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" name="type" value="<?php echo POST_TYPE_TEXT; ?>">
<textarea rows="3" cols="50" name="data" placeholder="Escribe algo..."></textarea><br>
<input type="submit" value="Publicar">
</form>
</p>

<?php

	$posts = post_get_all($con, $event_id, $user_id);
	if ($posts === FALSE)
	{
		echo '<p>'.mysql_error().'</p>';
	}
	else
	{
		sort_by_created($posts);
		foreach ($posts as $post)
		{
			get_post($post, $users);
		}
	}

}

function get_post($post, $users)
{
	$user = $users[$post->user_id];
	$text = html_text($post->data);
	echo "<p>{$user->name} ({$post->created})</p><p>{$text}</p>";
}

function get_guests_list($title, $guests, $status, $event)
{
	$guests = sorted_users_with_status($guests, $status);

	if (count($guests) > 0)
	{
		echo "<h4>{$title}</h4>";
		echo '<ul>';
		foreach ($guests as $guest)
		{
			if ($guest->id == $event->owner)
			{
				echo "<li>{$guest->name} (Organizador)</li>";
			}
			else
			{
				echo "<li>{$guest->name}</li>";
			}
		}
		echo '</ul>';
	}
}

?>