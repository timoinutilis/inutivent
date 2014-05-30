<?php

require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/../../includes/pageutils.php');
require_once(dirname(__FILE__).'/../../includes/config.php');

function send_owner_mail($mail, $event, $user)
{
	$subject = filter_subject("Tu evento {$event->title}");
	$base_url = SITE_URL;
	$message = <<<END
Hola {$user->name},

aquí tienes el link para acceder a la página de tu evento {$event->title}:
{$base_url}/event.php?event={$event->id}&user={$user->id}

No lo pierdas: es tu unica manera de acceder a la página del evento.
No lo compartas: es tu acceso personal. Borra la historia del navegador después de usar un ordenador público.

END;

	$headers = 'From: '.SENDER_MAIL_ADDRESS;
	return mail($mail, $subject, $message, $headers);
}

function send_invitation_mail($mail, $event, $owner, $user, $information)
{
	$subject = filter_subject($event->title);
	$base_url = SITE_URL;
	$time = date_of_datetime($event->time)." a la(s) ".hour_of_datetime($event->time);
	$message = <<<END

{$event->details}

{$time}

{$information}

----

Contesta en la página del evento, esto es tu link personal:
{$base_url}/event.php?event={$event->id}&user={$user->id}

No lo pierdas: es tu unica manera de acceder a la página del evento.
No lo compartas: es tu acceso personal. Borra la historia del navegador después de usar un ordenador público.

----

En Inutivent puedes invitar gente a tus eventos, mirar quién va a asistir y hablar con los invitados en el muro.
Y todo en la manera más simple, sin registración ni log-in.

END;

	$headers = 'From: '.filter_name($owner->name).' via '.SENDER_MAIL_ADDRESS;
	return mail($mail, $subject, $message, $headers);
}

// Filter Name
function filter_name( $input )
{
	$rules = array( "\r" => '', "\n" => '', "\t" => '', '"' => "'", '<'  => '[', '>'  => ']' );
	$name = trim( strtr( $input, $rules ) );
	return $name;
}

// Filter Email
function filter_subject( $input )
{
	$rules = array( "\r" => '', "\n" => '', "\t" => '');
	$subject = strtr( $input, $rules );
	return $subject;
}

?>