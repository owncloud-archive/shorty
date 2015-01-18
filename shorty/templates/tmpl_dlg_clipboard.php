<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2014 Christian Reiner <foss@christian-reiner.info>
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
 * @file templates/tmpl_dlg_clipboard.php
 * Dialog popup to copy url for further usage
 * @access public
 * @author Christian Reiner
 */
?>

<!-- begin of clipboard dialog -->
<div id="dialog-clipboard" style="display:none;">
	<fieldset class="">
		<legend><?php p(OC_Shorty_L10n::t("Copy to clipboard")); ?>:</legend>
		<div class="usage-explanation">
			<?php p(OC_Shorty_L10n::t("The link below can be copied for further usage")); ?>:
		</div>
		<input class="payload" readonly="true">
		<div class="usage-instruction">
			<?php p(OC_Shorty_L10n::t("Copy to clipboard")); ?>:<span class="usage-token"><?php p(OC_Shorty_L10n::t("Ctrl-C")); ?></span>
		</div>
  </fieldset>
</div>
<!-- end of clipboard dialog -->
