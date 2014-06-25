<?php

/*
INVITE
*/

?>

				<div id="cover" class="section">
					<div class="wrapper">
						<img src="<?php header_image_url(); ?>">
						<h1><?php echo _('Invite to'); ?><br><?php event_title(); ?></h1>
					</div>
					<br style="clear: both; width: 100%;">
				</div>

				<div id="invite" class="section">
					<div class="inside big-padding">

						<form action="backend/invite.php" method="POST" onsubmit="return onSubmit(event)">
							<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
							<input type="hidden" name="locale" value="<?php page_locale(); ?>">
							<div class="group">
								<?php echo _('E-Mail Addresses: *'); ?><br>
								<textarea rows="10" name="mails" id="mails"></textarea><br>
								<span class="footnote"><?php echo _('* Separated by new lines, "," or ";". If you use the format "Name &lt;mail@example.com&gt;" the guest\'s name gets saved already, otherwise it\'s "???" until they change it.'); ?></span>
							</div>
							<div class="group">
								<?php echo _('Optional Private Information (Your Address, Telephone Number): *'); ?><br>
								<textarea rows="5" name="information" id="mails"></textarea><br>
								<span class="footnote"><?php echo _('* The information will only be sent by e-mail, it won\'t be saved.'); ?></span>
							</div>
							<input type="submit" value="<?php echo _('Send mail(s)'); ?>" class="big-button">
							<a href="<?php event_url(); ?>"><?php echo _('Go to event\'s page'); ?></a>
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
						if (data.failed.length > 0)
						{
							document.getElementById('mails').value = data.failed.join("\n");

							var alertText = formatString("<?php echo _('{0} invitation(s) were sent correctly.\nThere were errors with:\n{1}'); ?>", [data.num_sent, data.failed.join(", ")]);
							alert(alertText);
						}
						else
						{
							window.location.href = "<?php event_url(); ?>";
						}
					}

					function onError(error)
					{
						alert(error);
					}

				</script>

				
