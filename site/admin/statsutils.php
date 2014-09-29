<?php

/*
STATS UTILS
*/

function print_table($title, $con, $query, $single_object = FALSE)
{
	if ($title)
	{
		echo "<h3>{$title}</h3>\n";
	}

	$result = mysqli_query($con, $query);
	if ($result)
	{
		$objects = array();
		$any_object;
		while ($object = mysqli_fetch_object($result))
		{
			$objects[] = $object;
			$any_object = $object;
		}

		if (!isset($any_object))
		{
			echo "No results<br>\n";
		}
		else if ($single_object)
		{
			echo "<table>\n";
			$vars = get_object_vars($any_object);
			foreach ($vars as $key => $value)
			{
				echo "<tr><td>$key:</td><td>$value</td></tr>\n";
			}
			echo "</table>\n";
		}
		else
		{
			echo "<table border=\"1\"><tr>\n";
			$vars = get_object_vars($any_object);
			foreach ($vars as $key => $value)
			{
				echo "<th>$key</th>\n";
			}
			echo "</tr>\n";
			foreach ($objects as $object)
			{
				echo "<tr>\n";
				$vars = get_object_vars($object);
				foreach ($vars as $value)
				{
					$value = str_replace("\n", '<br>', $value);
					echo "<td>$value</td>\n";
				}
				echo "</tr>\n";
			}
			echo "</table>\n";
		}
	}
	else
	{
		echo "Error query: ".mysqli_error($con)."<br>\n";
	}

}

?>
