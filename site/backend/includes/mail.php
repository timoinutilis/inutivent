<?php

require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/../../includes/pageutils.php');
require_once(dirname(__FILE__).'/../../includes/config.php');

$mail_template = file_get_contents(dirname(__FILE__).'/mailtemplate.html');

function send_owner_mail($mail, $event, $user)
{
	$subject = sprintf( _('Your event "%s"'), $event->title);

	$text = sprintf( _('Hello %s,'), $user->name)."\n\n"._('Here you have the access to the webpage of your event.');
	$message = create_mail($event->title, $text, $event->id, $user->id);

	$headers  = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
	$headers .= 'From: '.SENDER_MAIL_ADDRESS;

	return mail($mail, $subject, $message, $headers);
}

function send_invitation_mail($mail, $event, $owner, $user, $information)
{
	$subject = $event->title;

	$text = $event->details."\n\n".$information;
	$message = create_mail($event->title, $text, $event->id, $user->id);

	$headers  = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
	$headers .= 'From: '.$owner->name.' via '.SENDER_MAIL_ADDRESS;

	return mail($mail, $subject, $message, $headers);
}

function create_mail($title, $message, $event_id, $user_id)
{
	global $mail_template;

	$title = html_text($title);
	$message = html_text($message);
	$web_button = html_text( _('Visit Event\'s Webpage'));
	$app_button = html_text( _('Open in App (iOS)'));
	$access_info = html_text( _('This is your personal access. Don\'t share it and clean the browser history after using public computers.'));
	$footer = html_text( _('Gromf helps you inviting friends to your events, without registration and without collecting personal information.'));

	$web_url = SITE_URL."/event.php?event={$event_id}&user={$user_id}";
	$app_url = "gromf://?event={$event_id}&user={$user_id}";

	$mail = sprintf($mail_template, $title, $message, $web_url, $web_button, $app_url, $app_button, $access_info, $footer);
	return $mail;
}

?>