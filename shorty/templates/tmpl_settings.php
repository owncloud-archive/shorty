<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2013 Christian Reiner <foss@christian-reiner.info>
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
<fieldset id="shorty-fieldset" class="personalblock">
	<legend>
		<span id="title" class="shorty-title">
			<img class="svg" style="vertical-align: bottom;"
				src="<?php p(OCP\Util::imagePath("shorty","shorty-dusky.svg")); ?> ">
			<a name="shorty"><strong>Shorty</strong></a>
		</span>
	</legend>
	<form id="shorty">
		<div id="backend-static" class="backend-supplement">
			<span><?php p(OC_Shorty_L10n::t("Optional configuration of a 'Static Backend'").":"); ?></span>
			<br/>
			<label for="backend-static-base" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Base url").":"); ?></label>
			<input id="backend-static-base" type="text" name="backend-static-base"
					value="<?php p($_['backend-static-base']); ?>"
					maxlength="256" placeholder="<?php p(OC_Shorty_L10n::t('Specify a static base url…')); ?>" style="width:25em;">
			<br/>
			<label for="backend-example" class="shorty-aspect"> </label>
			<span id="backend-example">
				<label for="example" class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Example").":"); ?></label>
				<a id="example" class="shorty-example" title="<?php p(OC_Shorty_L10n::t("Verify by clicking…")); ?>">
				<?php print_unescaped(sprintf(htmlspecialchars('http://%s/<service><shorty id>'),$_SERVER['SERVER_NAME'])); ?>
				</a>
			</span>
			<br/>
			<span id="explain" class="shorty-explain"><?php print_unescaped(sprintf("%1\$s<br />\n%2\$s<br />\n%3\$s <span class=\"shorty-example\">%6\$s</span><br />\n%4\$s<br />\n%5\$s",
				OC_Shorty_L10n::t("Static, rule-based backend, generates shorty links relative to a given base url."),
				OC_Shorty_L10n::t("You have to take care that any request to the url configured here is internally mapped to the 'shorty' module."),
				OC_Shorty_L10n::t("The target of that mapping must be some URL like:"),
				OC_Shorty_L10n::t("Have a try with the example link provided, click it, it should result in a confirmation that your setup is working."),
				OC_Shorty_L10n::t("Leave empty if you can't provide a short base url that is mapped the described way."),
				htmlspecialchars('http://<domain>/<owncloud>/public.php?service=shorty_relay&id=<shorty id>'))); ?>
			</span>
		</div>
	</form>
<!-- list of installed plugins -->
	<div class="shorty-aspect"><?php p(OC_Shorty_L10n::t("Plugins").":"); ?></div>
<?php 	foreach ( $_['shorty-plugins']['shorty'] as $plugin ) { ?>
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
	<p>
		<span id="plugins" class="suggestion">
			<?php print_unescaped(sprintf(OC_Shorty_L10n::t("The additional plugin '%%s' tracks the usage of existing Shortys!"),
												'<strong>Shorty Tracking</strong>')); ?>
			<br/>
			<?php print_unescaped(sprintf(OC_Shorty_L10n::t("It can be enabled by a single click in the administration:")." %s",
								'<a href="'.OCP\Util::linkToAbsolute("settings", "apps.php").'" class="clickable">'.
								'<button>'.OC_Shorty_L10n::t("Apps selector").'</button>'.
								'</a>' )); ?>
		</span>
	</p>
<?php } ?>
</fieldset>
