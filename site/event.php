<?php

require_once(dirname(__FILE__).'/includes/page.php');
require_once(dirname(__FILE__).'/backend/includes/database.php');

$event_id = $_REQUEST['event'];
$user_id = $_REQUEST['user'];

get_header();

$con = connect_to_db();
if ($con)
{
	$event = event_get($con, $event_id);
	if ($event === FALSE)
	{
		echo 'Evento no encontrado!';
	}
	else
	{
		$is_owner = ($event->owner == $user_id);

		echo <<<END
<h2>{$event->title}</h2>
<p>
{$event->details}
</p>
END;
		if ($is_owner)
		{
			$invite_link = "invite.php?event={$event_id}&user={$user_id}";
			echo <<<END
<p>
<a href="{$invite_link}">Invitar</a>
</p>
END;
		}
	}
}
else
{
	echo mysql_error();
}

get_footer();

?>