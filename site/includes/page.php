<?php

function get_header()
{
	echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<title>Inutivent</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="all" href="style.css" />
</head>

<body>
<h1>Inutivent</h1>

END;
}

function get_footer()
{
	echo <<<END

</body>

</html>
END;
}

?>