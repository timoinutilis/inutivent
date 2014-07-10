<?php

require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/../../includes/pageutils.php');
require_once(dirname(__FILE__).'/../../includes/config.php');

$mail_template = file_get_contents(dirname(__FILE__).'/mailtemplate.html');

function send_owner_mail($mail, $event, $user)
{
	$from_name = SENDER_MAIL_NAME;

	$subject = sprintf( _('Your event "%s"'), $event->title);

	$text = sprintf( _('Hello %s,'), $user->name)."\n\n"._('Here you have the access to the webpage of your event.');
	$plain_message = create_plain_message($event->title, $text, $event->id, $user->id);
	$html_message = create_html_message($event->title, $text, $event->id, $user->id);

	return send_mail($mail, $from_name, $subject, $plain_message, $html_message);
}

function send_invitation_mail($mail, $event, $owner, $user, $information)
{
	$from_name = $owner->name.' via '.SENDER_MAIL_NAME;

	$subject = $event->title;

	$text = $event->details."\n\n".$information;
	$plain_message = create_plain_message($event->title, $text, $event->id, $user->id);
	$html_message = create_html_message($event->title, $text, $event->id, $user->id);

	return send_mail($mail, $from_name, $subject, $plain_message, $html_message);
}

function send_mail($to, $from_name, $subject, $plain_message, $html_message)
{
	mb_internal_encoding("UTF-8");

	$boundary = uniqid('part_');

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: multipart/alternative;boundary={$boundary}\r\n";
	$headers .= "From: ".mb_encode_mimeheader($from_name, "UTF-8", "Q"). " <".SENDER_MAIL_ADDRESS.">\r\n";
	$headers .= "Reply-To: ".REPLY_MAIL_ADDRESS;

	$message = "This is a MIME message.\r\n";
	$message .= "--{$boundary}\r\n";

	//Plain text body
	$message .= "Content-type: text/plain;charset=utf-8\r\n";
	$message .= "Content-Transfer-Encoding: quoted-printable\r\n";
	$message .= "Content-ID: text-body\r\n\r\n";
	$message .= quoted_printable_encode($plain_message);
	$message .= "\r\n--{$boundary}\r\n";

	//Html body
	$message .= "Content-type: text/html;charset=utf-8\r\n";
	$message .= "Content-Transfer-Encoding: quoted-printable\r\n";
	$message .= "Content-ID: html-body\r\n\r\n";
	$message .= quoted_printable_encode($html_message);
	$message .= "\r\n--{$boundary}--";

	// encode some headers

	$matches = array();
	preg_match('/<(.*?)>/s', $to, $matches);
	if (count($matches) > 1)
	{
		$to_name = strstr($to, " <", TRUE);
		$to_address = $matches[1];
		$to = mb_encode_mimeheader($to_name, "UTF-8", "Q"). " <{$to_address}>";
	}

	$subject = mb_encode_mimeheader($subject, "UTF-8", "Q");

	return mail($to, $subject, $message, $headers);
}

function create_plain_message($title, $message, $event_id, $user_id)
{
	$web_url = SITE_URL."/event.php?event={$event_id}&user={$user_id}";
	$app_url = "gromf://?event={$event_id}&user={$user_id}";

	$mail = "{$message}\r\n\r\n\r\n";
	$mail .= _('Visit Event\'s Webpage').": {$web_url}\r\n";
	$mail .= _('Open in App (iOS)').": {$app_url}\r\n\r\n";
	$mail .= get_mail_access_info()."\r\n\r\n";
	$mail .= get_mail_footer()."\r\n";

	return $mail;
}

function create_html_message($title, $message, $event_id, $user_id)
{
	global $mail_template;

	$title = html_text($title);
	$message = html_text($message);
	$web_button = html_text( _('Visit Event\'s Webpage'));
	$app_button = html_text( _('Open in App (iOS)'));
	$access_info = html_text(get_mail_access_info());
	$footer = html_text(get_mail_footer());

	$web_url = SITE_URL."/event.php?event={$event_id}&user={$user_id}";
	$app_url = "gromf://?event={$event_id}&user={$user_id}";

	$mail = sprintf($mail_template, $title, $message, $web_url, $web_button, $app_url, $app_button, $access_info, $footer);
	return $mail;
}

function get_mail_access_info()
{
	return _('This is your personal access. Don\'t share it and clean the browser history after using public computers.');
}

function get_mail_footer()
{
	return _('Gromf helps you to invite friends to your events, without the need to register and without collecting personal data. Your e-mail address was not saved.');
}

?>