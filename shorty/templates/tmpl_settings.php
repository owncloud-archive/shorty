<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2015 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/
?>

<?php
/**
 * @file templates/tmpl_settings.php
 * Dialog to change plugin settings, to be included in the clouds settings page.
 * @access public
 * @author Christian Reiner
 */
?>

<!-- settings of app 'shorty' -->
<div class="section" id="shorty">
	<h2>
		<span class="shorty-title">
			<img class="svg" src="<?php p(OCP\Util::imagePath("shorty","shorty-dusky.svg")); ?>">
			Shorty
		</span>
	</h2>
	<fieldset id="shorty-backend-configuration" class="shorty-backend-supplement">
		<legend><?php p(OC_Shorty_L10n::t("Default backend suggested inside users preferences").":"); ?></legend>
		<label for="shorty-backend-default" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Backend").":"); ?></label>
		<select id="shorty-backend-default" name="backend-default" value="<?php p($_['backend-default']); ?>">
<?php foreach ( OC_Shorty_Type::$BACKENDS as $backend_key=>$backend_name ) { ?>
			<option value="<?php p($backend_key);?>" <?php p(($backend_key==$_['backend-default'])?'selected':'');?>>
				<?php p(OC_Shorty_L10n::t($backend_name));?>
			</option>
<?php } ?>
		</select>
	</fieldset>
	<fieldset class="shorty-backend-supplement">
		<legend><?php p(OC_Shorty_L10n::t("Optional configuration of a 'Static Backend'").":"); ?></legend>
		<div id="shorty-backend-static">
			<label for="shorty-backend-static-base" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Base url").":"); ?></label>
			<input id="shorty-backend-static-base" type="text" name="backend-static-base"
					value="<?php p($_['backend-static-base']); ?>"
					maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t('Specify a static base url…')); ?>" style="width:25em;">
			<!-- an general busy/activity indicator -->
			<div id="shorty-backend-static-verification"><img class="shorty-activity" src="<?php p(OCP\Util::imagePath('shorty', 'loading-led.gif')); ?>"></div>
		</div>
		<br/>
		<label for="shorty-backend-example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").":"); ?></label>
		<span id="shorty-backend-example" class="shorty-example">
			<?php p('http://<domain>/<service><shorty-id>'); ?>
		</span>
		<br/>
		<span class="shorty-explain">
			<?php print_unescaped(nl2br(sprintf("%1\$s\n%2\$s\n%3\$s\n<span class=\"shorty-example\">%6\$s</span>\n%4\$s\n%5\$s",
				OC_Shorty_L10n::t("Static, rule-based backend, generates shorty links relative to a given base url."),
				OC_Shorty_L10n::t("You have to take care that any request to the url configured here is internally mapped to the 'shorty' module."),
				OC_Shorty_L10n::t("The target of that mapping, typically by means of a rewriting rule, must be some URL like:"),
				OC_Shorty_L10n::t("Such rewriting rules should be configured inside the main http server configuration."),
				OC_Shorty_L10n::t("If no access to that configuration exists, then using '.htaccess' style files might be an option."),
				htmlspecialchars('http://<domain>/<owncloud>/public.php?service=shorty_relay&id=<shorty-id>')))); ?>
		</span>
	</fieldset>
<!-- list of installed plugins -->
	<fieldset class="shorty-backend-supplement">
		<legend><?php p(OC_Shorty_L10n::t("Plugins").":"); ?></legend>
<?php foreach ( $_['shorty-plugins']['shorty'] as $plugin ) { ?>
		<div>
			<label for="shorty-plugin-<?php p(trim($plugin['id']));?>">
				<?php p(trim($plugin['name']).":");?>
			</label>
			<span class="shorty-plugin-<?php p(trim($plugin['id']));?>" class="shorty-example">
				<?php p(trim($plugin['abstract']));?>
			</span>
		</div>
<?php } ?>
<!-- list of suggested plugins -->
<?php if ( ! OCP\App::isEnabled('shorty_tracking') ) { ?>
		<div>
			<span id="shorty-plugins" class="suggestion">
				<?php print_unescaped(
								sprintf(OC_Shorty_L10n::t("The additional plugin '%%s' can track the usage of existing Shortys!"),
									'<strong>Shorty Tracking</strong>')); ?>
				<br/>
				<?php print_unescaped(
								sprintf(OC_Shorty_L10n::t('It has to be installed manually from the %%s.'),
									sprintf('<a href="%s" target="_blank">%s</a>',
										'http://apps.owncloud.com/content/show.php/Shorty+Tracking?content=152473',
										OC_Shorty_L10n::t('OwnCloud App Store')))); ?>
			</span>
		</div>
<?php } ?>
	</fieldset>
</div>
