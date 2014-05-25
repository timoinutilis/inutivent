<?php

require_once(dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/utils.php');

define('UPLOAD_MAX_BYTES', 3000000);
define('COVER_WIDTH', 640);
define('COVER_HEIGHT', 200);
define('COVER_MAX_BYTES', 30000);
define('COVER_JPG_QUALITY', 80);

if (   empty($_REQUEST['event_id'])
	|| empty($_REQUEST['user_id'])
	|| !isset($_FILES["file"])
	|| empty($_FILES["file"]["name"]) )
{
	return_error("missing parameters");
}
else
{
	$event_id = $_REQUEST['event_id'];
	$user_id = $_REQUEST['user_id'];

	$allowedExts = array("jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = strtolower(end($temp));

	$file_type = $_FILES["file"]["type"];
	$file_size = $_FILES["file"]["size"];

	if (  (($file_type == "image/jpeg")
		|| ($file_type == "image/jpg")
		|| ($file_type == "image/pjpeg")
		|| ($file_type == "image/x-png")
		|| ($file_type == "image/png"))
		&& ($file_size <= UPLOAD_MAX_BYTES)
		&& in_array($extension, $allowedExts))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			return_error("upload error ".$_FILES["file"]["error"]);
		}
		else
		{
			$uploads_path = dirname(__FILE__)."/../uploads/";

			$time_hash = hash('md5', microtime());
			$filename = $user_id."-".$time_hash.".";

			if (!is_dir($uploads_path.$event_id))
			{
				mkdir($uploads_path.$event_id);
			}

			// scale or original
			$temp_filename = $_FILES["file"]["tmp_name"];
			$image_info = getimagesize($temp_filename);
			$width = $image_info[0];
			$height = $image_info[1];

			if ($width != COVER_WIDTH || $height != COVER_HEIGHT || $file_size > COVER_MAX_BYTES)
			{
				// save down scaled image
				if ($file_type == "image/x-png" || $file_type == "image/png")
				{
					$image_orig = imagecreatefrompng($temp_filename);
				}
				else
				{
					$image_orig = imagecreatefromjpeg($temp_filename);
				}
				$image_scaled = imagecreatetruecolor(COVER_WIDTH, COVER_HEIGHT);
				$scale_factor = max(COVER_WIDTH / $width, COVER_HEIGHT / $height);
				$src_scaled_width = COVER_WIDTH / $scale_factor;
				$src_scaled_height = COVER_HEIGHT / $scale_factor;
				imagecopyresampled($image_scaled, $image_orig,
					0, 0,
					($width - $src_scaled_width) * 0.5, ($height - $src_scaled_height) * 0.5,
					COVER_WIDTH, COVER_HEIGHT,
					$src_scaled_width, $src_scaled_height);

				imagedestroy($image_orig);
				$filename .= "jpg";
				imagejpeg($image_scaled, $uploads_path.$event_id."/".$filename, COVER_JPG_QUALITY);
				imagedestroy($image_scaled);
			}
			else
			{
				// save original
				$filename .= $extension;
				move_uploaded_file($temp_filename, $uploads_path.$event_id."/".$filename);
			}


			$con = connect_to_db();
			if ($con)
			{
				if (event_update($con, $event_id, NULL, NULL, NULL, $filename))
				{
					$result = array('filename' => $filename);
					echo json_encode($result);
				}
				else
				{
					return_error(mysql_error());
				}
			}
			else
			{
				return_error(mysql_error());
			}

		}
	}
	else
	{
		return_error("invalid file format or file too big");
	}
}

?>