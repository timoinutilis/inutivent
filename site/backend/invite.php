<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');
require_once(dirname(__FILE__).'/includes/mail.php');

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id'])
	|| !isset($_REQUEST['mails']) )
{
	return_error("missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];
	$mails = $_REQUEST['mails'];

	$con = connect_to_db();
	if ($con)
	{
		$event = event_get($con, $event_id);
		$owner = user_get($con, $event_id, $user_id);
		if ($event === FALSE || $owner === FALSE)
		{
			return_error(mysql_error());
		}
		else
		{
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
						$name = $mail_parts[0];
//						$pure_mail = $mail_parts[count($mail_parts) - 1];
//						$pure_mail = substr($pure_mail, 1, strlen($pure_mail) - 2);

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

							if (send_invitation_mail($mail, $event, $owner, $guest_user))
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
			echo json_encode($result);
		}
	}
	else
	{
		return_error(mysql_error());
	}
}

?>