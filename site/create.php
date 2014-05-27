<?php

/*
CREATE EVENT
*/

?>

<?php include 'includes/header.php'; ?>

				<div id="event-create" class="section">
					<div class="inside">

						<h1>Crear nuevo evento</h1>

						<form action="backend/createevent.php" method="POST" onsubmit="return onSubmit(event)">
							<div class="group">
								<label for="title">Título:</label>
								<input type="text" id="title" name="title" placeholder="ejemplo: fiesta de cumpleaños"><br>
								
								<label for="date">Fecha:</label>
								<input type="text" id="date" name="date" placeholder="ejemplo: 24/12/2014"><br>
								
								<label for="hour">Hora:</label>
								<input type="text" id="hour" name="hour" placeholder="ejemplo: 20:00"><br>
								
								Detalles:<br>
								<textarea rows="10" name="details" placeholder="Escribe algo..."></textarea><br>
								<br>
								
								<label for="name">Tu nombre:</label>
								<input type="text" id="name" name="name"><br>
								
								<label for="mail">Tu e-mail:</label>
								<input type="email" id="mail" name="mail"><br>
							</div>
							<input type="submit" value="Listo" class="big-button">
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
						window.location.href = "invite.php?event=" + data.event_id + "&user=" + data.user_id;
					}

					function onError(error)
					{
						alert(error);
					}

				</script>

<?php include 'includes/footer.php'; ?>
