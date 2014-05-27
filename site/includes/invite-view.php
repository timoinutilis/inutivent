<?php

/*
INVITE
*/

?>

				<div id="cover" class="section">
					<div class="wrapper">
						<img src="<?php header_image_url(); ?>">
						<h1>Invitar a<br><?php event_title(); ?></h1>
					</div>
					<br style="clear: both; width: 100%;">
				</div>

				<div id="invite" class="section">
					<div class="inside">

						<form action="backend/invite.php" method="POST" onsubmit="return onSubmit(event)">
							<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
							<div class="group">
								Direcciones de e-mail:<br>
								<textarea rows="10" name="mails" id="mails"></textarea><br>
							</div>
							<input type="submit" value="Enviar mail(s)" class="big-button">
							<a href="<?php event_url(); ?>">Ir a la página del evento</a>
						</form>

					</div>
				</div>

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
							window.location.href = "<?php event_url(); ?>";
						}
					}

					function onError(error)
					{
						alert(error);
					}

				</script>

				
