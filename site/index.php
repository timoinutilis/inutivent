<?php

/*
HOME PAGE / INDEX
*/

require_once(dirname(__FILE__).'/includes/config.php');

?>

<?php include 'includes/header.php'; ?>

				<div id="cover" class="section">
					<div class="wrapper">
						<img src="images/index_header.jpg">
						<h1>¡Hola!</h1>
					</div>
					<br style="clear: both; width: 100%;">
				</div>

				<div id="welcome" class="section">
					<div class="inside">

						<p>En esta página puedes crear eventos y invitar a tus amigos por mail.</p>

						<form action="create.php">
							<input type="submit" value="Crear evento" class="big-button">
						</form>
					</div>
				</div>


<?php include 'includes/footer.php'; ?>
