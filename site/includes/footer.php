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
							<?php printf( _('Gromf helps you to invite friends to your events, without the need to register and without collecting personal data. <a href="%1$s">More information</a>'), SITE_URL); ?>
						</p>
						<a href="<?php echo SITE_URL; ?>"><img src="images/footer_logo_gromf.png"></a>
					</div>
					<div class="column right">
						<p>
							<?php printf( _('Website and illustrations by <a href="%1$s" onclick="%2$s">Timo Kloss</a><br>Icons from <a href="%3$s">IKONS</a><br>© 2014'), 'mailto:support', 'onClickMail(event)', get_external_url("http://www.ikons.piotrkwiatkowski.co.uk")); ?>
						</p>
						<a href="<?php external_url("http://www.inutilis.com"); ?>"><img src="images/footer_logo_inutilis.png"></a>
					</div>
					<br style="clear: both; width: 100%;">
				</div>
			</div>

		</div>

	</body>

</html>
