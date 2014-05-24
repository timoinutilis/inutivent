<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');
require_once(dirname(__FILE__).'/includes/mail.php');

if (   !isset($_REQUEST['event_id'])
	|| !isset($_REQUEST['user_id'])
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
			foreach ($mail_addresses as $mail)
			{
				$mail = trim($mail);
				if (strlen($mail) > 0)
				{
					$name = '???';

					// extract name
					$mail_parts = explode(' ', $mail);
					if (count($mail_parts) > 1)
					{
						$name = $mail_parts[0];
					}

					// create user and send mail
					$guest_user_id = user_create($con, $event_id, $name);
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
			$result = array('num_sent' => $num_sent, 'failed' => $failed);
			echo json_encode($result);
		}
	}
	else
	{
		return_error(mysql_error());
	}
}

?>