<?php

/*
External links will go through this redirection to hide the event URL from the referer.
*/

?><!DOCTYPE HTML>
<html>
	<head>
		<title>Redirect...</title>
	</head>
	<body>
		<script>

			window.location.href = "<?php echo $_REQUEST['url']; ?>";

		</script>
	</body>
</html>
