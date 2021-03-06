<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');
require_once(dirname(__FILE__).'/includes/mail.php');
require_once(dirname(__FILE__).'/../includes/config.php');

header('Content-type: application/json');

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id'])
	|| !isset($_REQUEST['mails'])
	|| !isset($_REQUEST['information']) )
{
	return_error(ERROR_MISSING_PARAMETERS, "Missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];
	$mails = $_REQUEST['mails'];
	$information = clean_string($_REQUEST['information']);
	$reply_to = !empty($_REQUEST['reply_to']) ? clean_string_line($_REQUEST['reply_to']) : NULL;
	$locale = !empty($_REQUEST['locale']) ? $_REQUEST['locale'] : 'en_US';

	$con = connect_to_db();
	if ($con)
	{
		$event = event_get($con, $event_id);
		$owner = user_get($con, $event_id, $user_id);
		if ($event === FALSE || $owner === FALSE)
		{
			return_error(ERROR_MYSQL, "MySQL error: ".db_error());
		}
		else if ($event === NULL)
		{
			return_error(ERROR_NOT_FOUND, "Event not found");
		}
		else if ($owner === NULL)
		{
			return_error(ERROR_NOT_FOUND, "User not found");
		}
		else if ($event->owner != $user_id)
		{
			return_error(ERROR_NO_PERMISSION, "No permission");
		}
		else if ($reply_to && !filter_var($reply_to, FILTER_VALIDATE_EMAIL))
		{
			return_error(ERROR_INVALID_PARAMETERS, "'Reply to' address is invalid");
		}
		else
		{
			// locale/gettext init
			putenv("LANG={$locale}");
			setlocale(LC_ALL, $locale);
			bindtextdomain(TEXT_DOMAIN, dirname(__FILE__).'/../locale');
			bind_textdomain_codeset(TEXT_DOMAIN, 'UTF-8');
			textdomain(TEXT_DOMAIN);

			$mail_addresses = preg_split('/[\n,;]+/', $mails);
			$num_sent = 0;
			$failed = array();
			$debug = array();
			$user_id_spare_obj = new stdClass();
			foreach ($mail_addresses as $mail)
			{
				$mail = trim($mail);
				if (strlen($mail) > 0)
				{
					$pure_mail = $mail;
					$name = '???';

					// parse mail
					$mail_parts = explode(' ', $mail);
					if (count($mail_parts) > 1)
					{
						$name = clean_string_line($mail_parts[0]);

						$matches = array();
						$t = preg_match('/<(.*?)>/s', $mail, $matches);
						if (count($matches) > 1)
						{
							$pure_mail = $matches[1];
						}
					}

					$debug[] = $mail." ".$name." ".$pure_mail;

					$valid = filter_var($pure_mail, FILTER_VALIDATE_EMAIL);
					if (!$valid)
					{
						$failed[] = $mail;
					}
					else
					{
						// create user and send mail
						$guest_user_id = user_create($con, $event_id, $name, STATUS_UNKNOWN, $user_id_spare_obj);
						if ($guest_user_id === FALSE)
						{
							$failed[] = $mail;
						}
						else
						{
							$guest_user = new stdClass();
							$guest_user->id = $guest_user_id;

							if (send_invitation_mail($mail, $event, $owner, $guest_user, $information, $reply_to))
							{
								$num_sent++;
							}
							else
							{
								$failed[] = $mail;
							}
						}
					}
				}
			}
			$result = array('num_sent' => $num_sent, 'failed' => $failed, 'debug' => $debug);
			if ($num_sent > 0)
			{
				$users = user_get_all($con, $event_id);
				if ($users === FALSE)
				{
					// ignore error
				}
				else
				{
					$result['users'] = $users;
				}
			}
			echo json_encode($result);
		}
	}
	else
	{
		return_error(ERROR_MYSQL, "MySQL error: ".db_error());
	}
}

?>