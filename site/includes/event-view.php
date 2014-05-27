<?php

/*
EVENT VIEW
*/

?>


				<div id="cover" class="section">
					<div class="wrapper">
						<img src="<?php header_image_url(); ?>">
						<h1><?php event_title(); ?></h1>
					</div>
					<br style="clear: both; width: 100%;">
				</div>

				<div id="event-info" class="section">
					<div class="inside">
						<div class="facts">
							<div class="fact">
								<img src="images/calendar.png">
								<p><?php echo event_date(); ?></p>
							</div>

							<div class="fact">
								<img src="images/time.png">
								<p><?php echo event_hour(); ?></p>
							</div>

							<div class="fact">
								<img src="images/user.png">
								<p><?php echo event_owner_name(); ?></p>
							</div>
						</div>

						<br style="clear: both; width: 100%;">

						<div class="details">
							<?php echo event_details(); ?>
						</div>
					</div>
				</div>

				<div id="user-info" class="section">
					<div class="inside">
						<img src="images/calendar_ok.png">
						<div class="name">
							<form action="backend/updateuser.php" method="POST" onsubmit="return onNameSubmit(event)">
								<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
								<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
								Tu nombre: <input type="text" id="input-name" name="name" value="<?php echo user_name(); ?>">
								<input type="submit" value="Guardar">
							</form>
						</div>

						<div class="status">
							<button type="button" <?php echo status_button(STATUS_ATTENDING); ?>>Asistir</button>
							<button type="button" <?php echo status_button(STATUS_MAYBE_ATTENDING); ?>>Tal vez asistir</button>
							<button type="button" <?php echo status_button(STATUS_NOT_ATTENDING); ?>>No asistir</button>
						</div>

					</div>
				</div>

				<div id="event-content" class="section">
					<div class="inside">
						<div id="posts">
							<img src="images/speech_4.png">
							<h1>Publicaciones</h1>
							<?php posts(); ?>

							<form action="backend/post.php" method="POST" onsubmit="return onPostSubmit(event)">
							<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
							<input type="hidden" name="type" value="<?php echo POST_TYPE_TEXT; ?>">
							<textarea rows="3" name="data" placeholder="Escribe algo..."></textarea><br>
							<input type="submit" value="Publicar">
							</form>
						</div>

						<div id="people">
							<img src="images/users.png">
							<h1>Invitados</h1>
							<?php guest_list(); ?>
						</div>

						<br style="clear: both; width: 100%;">
					</div>
				</div>

				<script>

					function submitAssist(status)
					{
						var formData = new FormData();
						formData.append("event_id", "<?php echo $event_id; ?>");
						formData.append("user_id", "<?php echo $user_id; ?>");
						formData.append("status", status);
						var name = document.getElementById("input-name").value;
						if (name != "<?php user_name(); ?>")
						{
							formData.append("name", name);
						}
						sendFormData("POST", "backend/updateuser.php", formData, onComplete, onError)
						setFormsDisabled(true);
					}

					function onNameSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						setFormsDisabled(true);
						return false;
					}

					function onCoverSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						setFormsDisabled(true);
						return false;
					}

					function onPostSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						setFormsDisabled(true);
						return false;
					}

					function onEventSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						setFormsDisabled(true);
						return false;
					}

					function onComplete(data)
					{
						setFormsDisabled(false);
						window.location.reload();
					}

					function onError(error)
					{
						setFormsDisabled(false);
						alert(error);
					}

					function showTitleEditor()
					{
						var data = <?php echo json_encode($event->title); ?>;
						document.getElementById("title-display").style.display = "none";
						document.getElementById("title-editor").style.display = "block";
						document.getElementById("input-title").value = data;
					}

					function showTimeEditor()
					{
						var data = <?php echo json_encode($event->time); ?>;
						document.getElementById("time-display").style.display = "none";
						document.getElementById("time-editor").style.display = "block";
						document.getElementById("input-time").value = data;
					}

					function showDetailsEditor()
					{
						var data = <?php echo json_encode($event->details); ?>;
						document.getElementById("details-display").style.display = "none";
						document.getElementById("details-editor").style.display = "block";
						document.getElementById("textarea-details").value = data;
					}

					function hideEditor(displayDiv, editorDiv, valueInput)
					{
						document.getElementById(displayDiv).style.display = "block";
						document.getElementById(editorDiv).style.display = "none";
						document.getElementById(valueInput).value = "";
					}

				</script>

