<?php

require_once(dirname(__FILE__).'/includes/page.php');
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
		echo 'Evento no encontrado!';
	}
	else
	{
		$event_link = "event.php?event={$event_id}&user={$user_id}";
?>

<h2>Invitar a <?php echo $event->title; ?></h2>

<p>
<form action="backend/invite.php" method="POST" onsubmit="return onSubmit(event)">
Direcciones de e-mail:<br>
<textarea rows="10" cols="50" name="mails" id="mails"></textarea><br>
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="submit" value="Enviar mail(s)">
</form>
</p>

<p>
<a href="<?php echo $event_link; ?>">Ir al evento</a>
</p>

<script>

function onSubmit(event)
{
	var form = event.target;
	sendForm(form, onComplete, onError);
	return false;
}

function onComplete(data)
{
	if (data.failed.length > 0)
	{
		document.getElementById('mails').value = data.failed.join("\n");
		alert("Se han enviado " + data.num_sent + " invitacion(es) correctamente.\nHubo errores con:\n" + data.failed.join(", "));
	}
	else
	{
		window.location.href = "<?php echo $event_link; ?>";
	}
}

function onError(error)
{
	alert(error);
}

</script>

<?php

	}
}

get_footer();

?>