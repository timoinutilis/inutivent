<?php

require_once(dirname(__FILE__).'/../../includes/config.php');
require_once(dirname(__FILE__).'/utils.php');

define('UPLOAD_MAX_BYTES', 6000000);
define('COVER_MAX_WIDTH', 1024);
define('COVER_MAX_HEIGHT_FACTOR', 0.375);
define('COVER_MAX_BYTES', 100000);
define('COVER_JPG_QUALITY', 80);

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

function save_cover_for_event($event_id, $file)
{
	$allowedExts = array("jpeg", "jpg", "png");
	$temp = explode(".", $file["name"]);
	$extension = strtolower(end($temp));

	$file_type = $file["type"];
	$file_size = $file["size"];

	if (  (($file_type == "image/jpeg")
		|| ($file_type == "image/jpg")
		|| ($file_type == "image/pjpeg")
		|| ($file_type == "image/x-png")
		|| ($file_type == "image/png"))
		&& ($file_size <= UPLOAD_MAX_BYTES)
		&& in_array($extension, $allowedExts))
	{
		if ($file["error"] > 0)
		{
			return make_error(ERROR_FAILED_UPLOAD_FILE, "Upload error: ".$file["error"]);
		}
		else
		{
			$uploads_path = dirname(__FILE__)."/../../uploads/";

			$time_hash = hash('md5', microtime());
			$filename = $time_hash.".";

			if (!is_dir($uploads_path.$event_id))
			{
				mkdir($uploads_path.$event_id);
			}

			// scale or original
			$temp_filename = $file["tmp_name"];
			$image_info = getimagesize($temp_filename);
			$width = $image_info[0];
			$height = $image_info[1];

			if ($width > COVER_MAX_WIDTH || $height > ($width * COVER_MAX_HEIGHT_FACTOR) || $file_size > COVER_MAX_BYTES)
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
				$cover_width = min($width, COVER_MAX_WIDTH);
				$scale = $cover_width / COVER_MAX_WIDTH;
				$cover_height = min(floor($height * $scale), floor($cover_width * COVER_MAX_HEIGHT_FACTOR));

				$image_scaled = imagecreatetruecolor($cover_width, $cover_height);
				$scale_factor = max($cover_width / $width, $cover_height / $height);
				$src_scaled_width = $cover_width / $scale_factor;
				$src_scaled_height = $cover_height / $scale_factor;
				imagecopyresampled($image_scaled, $image_orig,
					0, 0,
					($width - $src_scaled_width) * 0.5, ($height - $src_scaled_height) * 0.5,
					$cover_width, $cover_height,
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

			return array("filename" => $filename);
		}
	}
	else
	{
		return make_error(ERROR_INVALID_FILE, "Invalid file format or file too big");
	}
}

?>