<?php

/*
STATISTICS
*/

require_once(dirname(__FILE__).'/statsutils.php');
require_once(dirname(__FILE__).'/../includes/config.php');
require_once(dirname(__FILE__).'/../backend/includes/database.php');

?><!DOCTYPE HTML>
<html>
	<head>
		<title>Statistics</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<h1>Statistics</h1>
<?php

$from_date = isset($_GET['from']) ? $_GET['from'] : NULL;
$to_date = isset($_GET['to']) ? $_GET['to'] : NULL;

if (empty($from_date))
{
	$from_date = date('Y-m-d' , time() - 30 * 24 * 60 * 60);
}
if (empty($to_date))
{
	$to_date = date('Y-m-d H:i:s');
}

echo "<p>From {$from_date} to {$to_date}</p>\n";

$con = connect_to_db();
if (!$con)
{
	echo "Error connecting to database: ".db_error()."<br>\n";
}
else
{
	$from_date = mysqli_real_escape_string($con, $from_date);
	$to_date = mysqli_real_escape_string($con, $to_date);

	echo "<h2>Events</h2>\n";
	print_table(NULL, $con, "SELECT count(*) Created FROM events WHERE created >= '{$from_date}' AND created <= '{$to_date}'", TRUE);
	print_table("Locales", $con, "SELECT locale, count(*) amount FROM events WHERE created >= '{$from_date}' AND created <= '{$to_date}' GROUP BY locale ORDER BY amount DESC");

	echo "<h2>Users</h2>\n";
	print_table(NULL, $con, "SELECT count(*) Created FROM users WHERE created >= '{$from_date}' AND created <= '{$to_date}'", TRUE);
	print_table(NULL, $con, "SELECT count(*) 'Created and visited' FROM users WHERE created >= '{$from_date}' AND created <= '{$to_date}' AND visited > '0000-00-00 00:00:00'", TRUE);
	print_table(NULL, $con, "SELECT count(*) Visited FROM users WHERE visited >= '{$from_date}' AND visited <= '{$to_date}'", TRUE);

	echo "<h2>Posts</h2>\n";
	print_table(NULL, $con, "SELECT count(*) Created FROM posts WHERE created >= '{$from_date}' AND created <= '{$to_date}'", TRUE);

}

?>
	</body>
</html>
