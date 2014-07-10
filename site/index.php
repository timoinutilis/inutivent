<?php

/*
HOME PAGE / INDEX
*/

require_once(dirname(__FILE__).'/includes/config.php');
require_once(dirname(__FILE__).'/includes/init.php');

init_page(NULL, FALSE);

?>

<?php include 'includes/header.php'; ?>

				<div id="cover" class="section">
					<div class="wrapper">
						<img src="images/index_header.jpg">
						<h1><?php echo _('Hello!'); ?></h1>
					</div>
					<br style="clear: both; width: 100%;">
				</div>

				<div id="welcome" class="section">
					<div class="inside big-padding">
						<p><?php echo _('Gromf helps you to invite friends to your events, without the need to register and without collecting personal data.'); ?></p>
						<a href="create.php"><?php echo _('Create new event'); ?></a>
					</div>
				</div>

				<div id="welcome-why" class="section">
					<div class="inside small-padding">
						<img src="images/home_why.png">
						<div class="text">
							<h1><?php echo _('Why use Gromf?'); ?></h1>
							<ul class="list">
								<li><?php echo _('There is no registration and Gromf doesn\'t need a password.'); ?></li>
								<li><?php echo _('There are no user profiles. Gromf is really not into personal information.'); ?></li>
								<li><?php echo _('Gromf only asks for e-mail addresses to send invitations, he doesn\'t even try to remember them.'); ?></li>
								<li><?php echo _('Gromf doesn\'t care where you have been before, he keeps user information separate for each event.'); ?></li>
								<li><?php echo _('Gromf is hungry and can\'t wait for more than 30 days after the end of the event to eat all the data. Nothing will be left!'); ?></li>
							</ul>
						</div>
					</div>
				</div>

				<div id="welcome-how" class="section">
					<div class="inside big-padding">
						<h1><?php echo _('How does it work?'); ?></h1>
						<p>
							<?php printf(_('When you create an event and invite people to it, you and all the  guests will receive an e-mail with a personal link to the webpage of the event. This link already includes a number to identify the person, that\'s why there is no need to log in. These identification numbers are automatically created with every new event and with every invitation.<br><strong>Warning:</strong> Gromf’s priority is not to protect data (no passwords, no secure connections), but to make it anonymous and temporary. Don\'t publish very private information! If you have any queries you can <a href="%1$s" onclick="%2$s">contact me via e-mail</a>.'),
							'mailto:support',
							'onClickMail(event)'); ?>
						</p>
					</div>
				</div>


<?php include 'includes/footer.php'; ?>
