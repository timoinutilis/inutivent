<?php

/*
FOOTER
*/

require_once(dirname(__FILE__).'/pageutils.php');

?>
			</div>

			<div id="footer" class="section">
				<div class="inside">
					<div class="column">
						<p>
							<?php printf( _('With Inutivent you can invite people to your events without any registration. <a href="%1$s">More information</a>'), SITE_URL); ?>
						</p>
					</div>
					<div class="column right">
						<p>
							<?php printf( _('Website developed by <a href="%1$s" onclick="%2$s">Timo Kloss</a><br>Icons from <a href="%3$s">IKONS</a><br>An <a href="%4$s">inutilis</a> production<br>Copyright 2014 by Timo Kloss'), 'mailto:timo', 'onClickMail(event)', get_external_url("http://www.ikons.piotrkwiatkowski.co.uk"), get_external_url("http://www.inutilis.com")); ?>
						</p>
					</div>
					<br style="clear: both; width: 100%;">
				</div>
			</div>

		</div>

	</body>

</html>
