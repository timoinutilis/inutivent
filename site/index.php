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
						<p><?php echo _('Gromf helps you inviting friends to your events, without registration and without collecting personal data.'); ?></p>
						<a href="create.php"><?php echo _('Create new event'); ?></a>
					</div>
				</div>

				<div id="welcome-why" class="section">
					<div class="inside small-padding">
						<img src="images/home_why.png">
						<div class="text">
							<h1><?php echo _('Why?'); ?></h1>
							<ul class="list">
								<li><?php echo _('No registration and no login'); ?></li>
								<li><?php echo _('No user profiles'); ?></li>
								<li><?php echo _('No contact information saved on server'); ?></li>
								<li><?php echo _('Separated user data for each event'); ?></li>
								<li><?php echo _('All data will be deleted after event\'s end'); ?></li>
							</ul>
						</div>
					</div>
				</div>

				<div id="welcome-how" class="section">
					<div class="inside big-padding">
						<h1><?php echo _('How does it work?'); ?></h1>
						<p>
							<?php printf(_('When you create an event you will receive an e-mail with a personal link to access and edit your event. When you invite people everybody will get an e-mail with a personal link, too. E-mail addresses are never saved on the server. For each event independent users are created on the server, there is no connection between user data of different events.<br><br><strong>Warning:</strong> The personal links are the keys of this website, there is no password protection. Don\'t publish very private information! For any doubts you can contact me by <a href="%1$s" onclick="%2$s">e-mail</a>.'),
							'mailto:support',
							'onClickMail(event)'); ?>
						</p>
					</div>
				</div>


<?php include 'includes/footer.php'; ?>
