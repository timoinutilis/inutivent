<?php

function return_error($message)
{
	$error = array('error' => $message);
	echo json_encode($error);
}

?>