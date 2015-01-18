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
 * @file templates/tmpl_dlg_sms.php
 * Dialog popup to prepare sending a url via sms
 * @access public
 * @author Christian Reiner
 */
?>

<!-- begin of sms dialog -->
<div id="dialog-sms" style="display:none;">
	<fieldset class="">
		<legend><?php p(OC_Shorty_L10n::t("Prepare to send as SMS")); ?>:</legend>
		<div class="usage-explanation">
			<?php p(OC_Shorty_L10n::t("Clicking 'Ok' below will try to launch an sms composer")); ?>.
			<br>
			<?php p(OC_Shorty_L10n::t("Typically this only works on devices like smart phones")); ?>.
			<br>
			<?php p(OC_Shorty_L10n::t("Unfortunately the implementation of this scheme is limited")); ?>,
			<?php p(OC_Shorty_L10n::t("therefore the content must be copied manually")); ?>:
		</div>
		<textarea class="payload" readonly="true"></textarea>
		<div class="usage-instruction">
			<?php p(OC_Shorty_L10n::t("Copy to clipboard")); ?>:<span class="usage-token"><?php p(OC_Shorty_L10n::t("Ctrl-C")); ?></span>
			<br>
			…<?php p(OC_Shorty_L10n::t("after the SMS has been launched")); ?>…
			<br>
			<?php p(OC_Shorty_L10n::t("then paste into message")); ?>:<span class="usage-token"><?php p(OC_Shorty_L10n::t("Ctrl-V")); ?></span>
		</div>
	</fieldset>
</div>
<!-- end of sms dialog -->
