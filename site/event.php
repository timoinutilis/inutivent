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
<?php
			if ($is_owner)
			{
?>

<div id="title-display">
<button type="button" onclick="showTitleEditor();">Editar título</button>
</div>
<div id="title-editor" style="display:none;">
<form action="backend/updateevent.php" method="POST" onsubmit="return onEventSubmit(event)">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="text" name="title" id="input-title">
<input type="submit" value="Guardar">
<button type="button" onclick="hideEditor('title-display', 'title-editor', 'input-title');">Cancelar</button>
</form>
</div>
<?php
			}
?>

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
<input type="submit" value="Guardar">
</form>
</p>

<h3>Fecha</h3>
<p>
<?php
			if ($is_owner)
			{
?>

<div id="time-display">
<?php echo $event->time;?>
<button type="button" onclick="showTimeEditor();">Editar</button>
</div>
<div id="time-editor" style="display:none;">
<form action="backend/updateevent.php" method="POST" onsubmit="return onEventSubmit(event)">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="text" name="time" id="input-time">
<input type="submit" value="Guardar">
<button type="button" onclick="hideEditor('time-display', 'time-editor', 'input-time');">Cancelar</button>
</form>
</div>
<?php
			}
			else
			{
?>
<div id="time-display">
<?php echo $event->time;?>
</div>
<?php
			}
?>
</p>
<h3>Detalles</h3>
<?php
			if ($is_owner)
			{
?>
<div id="details-display">
<p>
<?php echo html_text($event->details); ?>
</p>
<button type="button" onclick="showDetailsEditor();">Editar</button>
</div>
<div id="details-editor" style="display:none;">
<form action="backend/updateevent.php" method="POST" onsubmit="return onEventSubmit(event)">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<textarea rows="10" cols="50" name="details" id="textarea-details"></textarea><br>
<input type="submit" value="Guardar">
<button type="button" onclick="hideEditor('details-display', 'details-editor', 'textarea-details');">Cancelar</button>
</form>
</div>
<?php
			}
			else
			{
?>
<div id="details-display">
<p>
<?php echo html_text($event->details); ?>
</p>
</div>

<?php
			}
?>

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
	var form = document.getElementById("form-assist");
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

function onEventSubmit(event)
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

function showTitleEditor()
{
	var data = <?php echo json_encode($event->title); ?>;
	document.getElementById("title-display").style.display = "none";
	document.getElementById("title-editor").style.display = "block";
	document.getElementById("input-title").value = data;
}

function showTimeEditor()
{
	var data = <?php echo json_encode($event->time); ?>;
	document.getElementById("time-display").style.display = "none";
	document.getElementById("time-editor").style.display = "block";
	document.getElementById("input-time").value = data;
}

function showDetailsEditor()
{
	var data = <?php echo json_encode($event->details); ?>;
	document.getElementById("details-display").style.display = "none";
	document.getElementById("details-editor").style.display = "block";
	document.getElementById("textarea-details").value = data;
}

function hideEditor(displayDiv, editorDiv, valueInput)
{
	document.getElementById(displayDiv).style.display = "block";
	document.getElementById(editorDiv).style.display = "none";
	document.getElementById(valueInput).value = "";
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