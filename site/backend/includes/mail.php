<?php

require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/../../includes/pageutils.php');
require_once(dirname(__FILE__).'/../../includes/config.php');

function send_owner_mail($mail, $event, $user)
{
	$subject = "Tu evento \"{$event->title}\"";
	$base_url = SITE_URL;
	$message = <<<END
Hola {$user->name},

aquí tienes el link para acceder a la página de tu evento:
{$base_url}/event.php?event={$event->id}&user={$user->id}

* No lo pierdas: es tu unica manera de acceder a la página del evento.
* No lo compartas: es tu acceso personal. Borra la historia del navegador después de usar un ordenador público.

END;

	$headers = 'From: '.SENDER_MAIL_ADDRESS;
	return mail($mail, $subject, $message, $headers);
}

function send_invitation_mail($mail, $event, $owner, $user, $information)
{
	$subject = $event->title;
	$base_url = SITE_URL;
	$time = date_of_datetime($event->time)." a la(s) ".hour_of_datetime($event->time);

	$message = <<<END

{$event->details}

{$information}

Visita la página del evento para más información y para contestar, esto es tu link personal:
{$base_url}/event.php?event={$event->id}&user={$user->id}

* No lo pierdas: es tu unica manera de acceder a la página del evento.
* No lo compartas: es tu acceso personal. Borra la historia del navegador después de usar un ordenador público.

END;

	$headers = 'From: '.$owner->name.' via '.SENDER_MAIL_ADDRESS;
	return mail($mail, $subject, $message, $headers);
}

?>