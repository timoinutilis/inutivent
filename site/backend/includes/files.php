<?php

require_once(dirname(__FILE__).'/../../includes/config.php');

function delete_files_of_event($event_id)
{
	// delete uploaded files
	$dir = dirname(__FILE__).'/../../uploads/'.$event_id;
	$files_ok = TRUE;
	if (is_dir($dir))
	{
		$files = glob($dir.'/*'); // get all file names
		foreach($files as $file)
		{
			if(is_file($file))
			{
				unlink($file); // delete file
			}
		}
		if (!rmdir($dir))
		{
			$files_ok = FALSE;
		}
	}
	return $files_ok;
}

?>