<?php

/*
CREATE EVENT
*/

require_once(dirname(__FILE__).'/includes/config.php');
require_once(dirname(__FILE__).'/includes/init.php');

init_page( _('New Event'), FALSE);

?>

<?php include 'includes/header.php'; ?>

				<div id="event-create" class="section">
					<div class="inside big-padding">

						<h1><?php echo _('New Event'); ?></h1>

						<form action="backend/createevent.php" method="POST" onsubmit="return onSubmit(event)">
							<div class="group">
								<label for="title"><?php echo _('Title:'); ?></label>
								<input type="text" id="title" name="title" placeholder="<?php echo _('ex: Birthday Party'); ?>"><br>
								
								<label for="date"><?php echo _('Date:'); ?></label>
								<input type="text" id="date" name="date" placeholder="<?php echo _('ex: 24/12/2014'); ?>"><br>
								
								<label for="hour"><?php echo _('Time:'); ?></label>
								<input type="text" id="hour" name="hour" placeholder="<?php echo _('ex: 20:00'); ?>"><br>
								
								<?php echo _('Details:'); ?>
								<span class="footnote"><?php echo _('(Don\'t put any private data like your address or telephone number here. You can do it when you are inviting.)'); ?></span><br>
								<textarea rows="10" name="details" placeholder="<?php echo _('Write something...'); ?>"></textarea><br>
								<br>
								
								<label for="name"><?php echo _('Your Name:'); ?></label>
								<input type="text" id="name" name="name"><br>
								
								<label for="mail"><?php echo _('Your E-Mail:'); ?></label>
								<input type="email" id="mail" name="mail" placeholder="<?php echo _('will not be saved'); ?>">
							</div>
							<button type="submit" class="big-button"><?php echo _('Create Event'); ?></button>
						</form>

					</div>
				</div>

				<script>

					function onSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						return false;
					}

					function onComplete(data)
					{
						window.location.href = "invite.php?event=" + data.event_id + "&user=" + data.user_id;
					}

					function onError(error)
					{
						alert(error);
					}

				</script>

<?php include 'includes/footer.php'; ?>
