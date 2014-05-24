<?php

require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/../../includes/config.php');

function send_owner_mail($mail, $event, $user)
{
	$subject = "Tu evento {$event->title}";
	$base_url = SITE_URL;
	$message = <<<END
Hola {$user->name},
aquí tienes el link para acceder a tu evento {$event->title}:
{$base_url}/event.php?event={$event->id}&user={$user->id}
END;
	return send_mail($mail, $subject, $message);
}

function send_invitation_mail($mail, $event, $owner, $user)
{
	$subject = "{$owner->name} te invita a {$event->title}";
	$base_url = SITE_URL;
	$message = <<<END
{$event->title}

{$event->time}

{$event->details}

Aquí tienes tu link personal para acceder al evento:
{$base_url}/event.php?event={$event->id}&user={$user->id}
END;
	return send_mail($mail, $subject, $message);
}

function send_mail($mail, $subject, $message)
{
	$headers = 'From: '.SENDER_MAIL_ADDRESS;
	return mail($mail, $subject, $message, $headers);
}

?>