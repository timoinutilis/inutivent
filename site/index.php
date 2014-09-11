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
						<p><a href="create.php"><?php echo _('Create new event'); ?></a></p>
						<p style="font-size:60%;"><a href="#heading-why">Why use Gromf?</a> - <a href="#heading-how">How does it work?</a> - <a href="#heading-faq">Frequently Asked Questions</a></p>
						<a href="<?php echo APP_STORE_URL; ?>"><img src="images/appstore.png"></a>
					</div>
				</div>

				<div id="more-info"></div>

				<div id="welcome-why" class="section">
					<div class="inside small-padding">
						<img src="images/home_why.png">
						<div class="text">
							<h1 id="heading-why"><?php echo _('Why use Gromf?'); ?></h1>
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
						<h1 id="heading-how"><?php echo _('How does it work?'); ?></h1>
						<p>
							<?php printf(_('When you create an event and invite people to it, you and all the guests will receive an e-mail with a personal link to the webpage of the event. This link already includes a number to identify the person, that\'s why there is no need to log in. These identification numbers are automatically created with every new event and with every invitation.<br><strong>Warning:</strong> Gromf’s priority is not to protect data (no passwords, no secure connections), but to make it anonymous and temporary. Don\'t publish very private information! If you have any queries you can <a href="%1$s" onclick="%2$s">contact me via e-mail</a>.'),
							'mailto:support',
							'onClickMail(event)'); ?>
						</p>
						<h1 class="heading-top-margin" id="heading-faq"><?php echo _('Frequently Asked Questions'); ?></h1>
						<h2><?php echo _('I\'m on gromf.com, where can I see my events?'); ?></h2>
						<p>
							<?php printf(_('You can not, because there are no user profiles which could collect your events. You have to open an event page with its link in the invitation e-mail or you can save it as a bookmark in your browser. You may want to use the <a href="%1$s">Gromf app for iOS</a>, which stores your event list locally on the phone.'), APP_STORE_URL); ?>
						</p>
						<h2><?php echo _('Why don\'t I receive any notification when there is something new on my event page?'); ?></h2>
						<p>
							<?php echo _('Gromf stores neither your e-mail address nor any other contact information, so there is no way to let you know.'); ?>
						</p>
					</div>
				</div>

<?php include 'includes/footer.php'; ?>
