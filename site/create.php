<?php

require_once('./includes/page.php');

get_header();
?>

<h2>Crear evento</h2>

<p>
<form action="backend/createevent.php" method="POST" onsubmit="return onSubmit(event)">
Título del evento: <input type="text" name="title"><br>
Fecha del evento: <input type="text" name="time" value="YYYY-MM-DD HH:MM:SS"><br>
Tu nombre: <input type="text" name="name"><br>
Tu e-mail: <input type="email" name="mail"><br>
Detalles:<br>
<textarea rows="10" cols="50" name="details"></textarea><br>
<input type="submit" value="Listo">
</form>
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
	window.location.href = "invite.php?event=" + data.event_id + "&user=" + data.user_id;
}

function onError(error)
{
	alert(error);
}

</script>

<?php
get_footer();

?>