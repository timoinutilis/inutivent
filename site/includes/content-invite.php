<?php

/*
INVITE
*/

?>

				<div id="invite" class="section">
					<div class="inside big-padding">

						<h1><?php echo _('Invite to'); ?> <?php event_title(); ?></h1>

						<form action="backend/invite.php" method="POST" onsubmit="return onSubmit(event)">
							<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
							<input type="hidden" name="locale" value="<?php page_locale(); ?>">
							<div class="group">
								<?php echo _('E-Mail Addresses:'); ?><br>
								<span class="footnote"><?php echo _('(Separated by new lines, "," or ";". Addresses will not be saved. If you use the format "Name&nbsp;&lt;mail@example.com&gt;" the guest\'s name gets saved already.)'); ?></span><br>
								<textarea rows="10" name="mails" id="mails"></textarea><br>
							</div>
							<div class="group">
								<?php echo _('Optional Private Information (Your Address, Telephone Number):'); ?><br>
								<span class="footnote"><?php echo _('(Will only be sent by e-mail, it won\'t be saved.)'); ?></span><br>
								<textarea rows="5" name="information" id="mails"></textarea><br>
							</div>
							<button type="submit" class="big-button"><?php echo _('Send mail(s)'); ?></button>
							<a href="<?php event_url(); ?>" style="margin-left: 10px;"><?php echo _('Go to event\'s page'); ?></a>
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

				
