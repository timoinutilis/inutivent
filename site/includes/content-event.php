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

				<?php if ($is_owner) { ?>
				<div id="cover-editor" class="section">
					<div class="inside">
						<form action="backend/uploadcover.php" method="post" enctype="multipart/form-data" onsubmit="return onCoverSubmit(event)">
							<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
							<label for="file">Foto de portada:</label>
							<input type="file" name="file" id="file">
							<input type="submit" name="submit" value="Subir">
						</form>
					</div>
				</div>
				<?php } ?>

				<div id="event-info" class="section">
					<div class="inside">
						<div id="event-display">
							<div class="facts">
								<div class="fact">
									<img src="images/calendar.png">
									<p><?php event_date(); ?></p>
								</div>

								<div class="fact">
									<img src="images/time.png">
									<p><?php event_hour(); ?></p>
								</div>

								<div class="fact">
									<img src="images/user.png">
									<p><?php event_owner_name(); ?></p>
								</div>
							</div>

							<br style="clear: both; width: 100%;">

							<div id="event-details-text" class="details">
								<?php event_details(); ?>
							</div>

							<?php if ($is_owner) { ?>
							<div class="button-container">
								<button type="button" onclick="showEventEditor();">Editar</button>
								<button type="button" onclick="onClickDeleteEvent();" class="delete">Borrar evento</button>
							</div>
							<?php } ?>
						</div>

						<?php if ($is_owner) { ?>
						<div id="event-editor" style="display:none;">
							<form action="backend/updateevent.php" method="POST" onsubmit="return onEventSubmit(event)">
								<div class="group">
									<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
									<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

									<label for="input-title">Título:</label>
									<input type="text" id="input-title" name="title" placeholder="ejemplo: fiesta de cumpleaños"><br>
									
									<label for="input-date">Fecha:</label>
									<input type="text" id="input-date" name="date" placeholder="ejemplo: 24/12/2014"><br>
									
									<label for="input-hour">Hora:</label>
									<input type="text" id="input-hour" name="hour" placeholder="ejemplo: 20:00"><br>

									<textarea rows="10" name="details" id="textarea-details"></textarea><br>
								</div>
								<input type="submit" value="Guardar">
								<button type="button" onclick="hideEditor();">Cancelar</button>
							</form>
						</div>
						<?php } ?>

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
							<div id="posts-content">
								<img src="images/speech_4.png">
								<h1>Comentarios</h1>
<?php posts(); ?>

								<form action="backend/post.php" method="POST" onsubmit="return onPostSubmit(event)">
									<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
									<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
									<input type="hidden" name="type" value="<?php echo POST_TYPE_TEXT; ?>">
									<textarea rows="3" name="data" placeholder="Escribe algo..."></textarea><br>
									<input type="submit" value="Publicar">
								</form>
							</div>
						</div>

						<div id="people">
							<img src="images/users.png">
							<h1>Invitados</h1>
<?php guest_list(); ?>
							<?php if ($is_owner) { ?>
							<br>
							<a href="<?php invite_url(); ?>">Invitar...</a>
							<?php } ?>
							
						</div>

						<br style="clear: both; width: 100%;">
					</div>
				</div>

				<script>

					window.onload = function() {
						linkTextURLs([document.getElementById("event-details-text")]);
						linkTextURLs(document.getElementsByName("post-text"));
					};

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
					}

					function onNameSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						return false;
					}

					function onCoverSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						return false;
					}

					function onPostSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						return false;
					}

					function onEventSubmit(event)
					{
						var form = event.target;
						sendForm(form, onComplete, onError);
						return false;
					}

					function onComplete(data)
					{
						window.location.reload();
					}

					function onError(error)
					{
						alert(error);
					}

					function showEventEditor()
					{
						var title = <?php echo json_encode($event->title); ?>;
						var date = "<?php event_date(); ?>";
						var hour = "<?php event_hour(); ?>";
						var details = <?php echo json_encode($event->details); ?>;
						
						document.getElementById("event-display").style.display = "none";
						document.getElementById("event-editor").style.display = "block";
						document.getElementById("cover-editor").style.display = "none";

						document.getElementById("input-title").value = title;
						document.getElementById("input-date").value = date;
						document.getElementById("input-hour").value = hour;
						document.getElementById("textarea-details").value = details;
					}

					function hideEditor()
					{
						document.getElementById('event-display').style.display = "block";
						document.getElementById('event-editor').style.display = "none";
						document.getElementById("cover-editor").style.display = "block";
						document.getElementById("input-title").value = "";
						document.getElementById("input-date").value = "";
						document.getElementById("input-hour").value = "";
						document.getElementById('textarea-details').value = "";
					}

					function onClickDeleteEvent()
					{
						if (confirm("Quieres borrar este evento de verdad?\nSe borran toda la información, las publicaciones y las fotos."))
						{
							var formData = new FormData();
							formData.append("event_id", "<?php echo $event_id; ?>");
							formData.append("user_id", "<?php echo $user_id; ?>");
							sendFormData("POST", "backend/deleteevent.php", formData, onCompleteDelete, onError)
						}
					}

					function onCompleteDelete(data)
					{
						window.location.href = "<?php echo SITE_URL; ?>";
					}

				</script>

