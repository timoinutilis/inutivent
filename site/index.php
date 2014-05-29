<?php

/*
HOME PAGE / INDEX
*/

require_once(dirname(__FILE__).'/includes/config.php');

?>

<?php include 'includes/header.php'; ?>

				<div id="cover" class="section">
					<div class="wrapper">
						<img src="images/index_header.jpg">
						<h1>¡Hola!</h1>
					</div>
					<br style="clear: both; width: 100%;">
				</div>

				<div id="welcome" class="section">
					<div class="inside">
						<p>Aquí puedes invitar gente a tus eventos, mirar quién va a asistir y hablar con los invitados en el muro.
							Y todo en la manera más simple.</p>
						<a href="create.php">Crear evento...</a>
					</div>
				</div>

				<div id="welcome-why" class="section">
					<div class="inside">
						<img src="images/home_why.png">
						<div class="text">
							<h1>¿Porqué?</h1>
							<ul class="list">
								<li>No hay registración.</li>
								<li>No hay log-in.</li>
								<li>No hay perfiles de usuarios.</li>
								<li>No se guardan informaciones de contacto (e-mail etc.).</li>
								<li>No es una red social.</li>
								<li>Al final puedes borrar todos los datos de un evento.</li>
								<li>Y bueno, es gratis, como todo.</li>
							</ul>
						</div>
					</div>
				</div>

				<div id="welcome-how" class="section">
					<div class="inside">
						<h1>¿Cómo funciona?</h1>
						<p>
							Tú creas un evento con toda su información necesaria. Entonces recibes un e-mail con un link
							a la página del evento. Ese link es personal y solo para ti, no lo compartas. Con tu acceso puedes
							editar información y tienes la posibilidad de borrar el evento.<br>
							Desde la página puedes invitar gente con sus direcciones de e-mail. Para cada invitado se crea
							una cuenta de usuario para este evento y se envian mails con links personales. Con sus accesos los
							invitados pueden confirmar que vengan y escribir en el muro.<br>
							Las direcciones de e-mail nunca se guardan, solo se usan para enviar los links y las invitaciones.<br>
							Las cuentas de usuarios solo sirven para un evento. Así cada evento tiene sus datos independientes y
							se quitan completamente al borrar el evento.<br>
							<br>
							<strong>Atención:</strong> Los links personales son las llaves de esta página, no hay protección por contraseñas.
							En teoría no debería ser posible encontrar un evento sin tener un link. De todos modos no deberías
							compartir información critica o muy privada!<br>
							De momento esta página es un proyecto personal y <strong>no hay garantía para la seguridad de los datos</strong>.
							Por cualquier duda me podéis <a href="mailto:timo" onclick="onClickMail(event)">contactar por e-mail</a>.
						</p>
					</div>
				</div>


<?php include 'includes/footer.php'; ?>
