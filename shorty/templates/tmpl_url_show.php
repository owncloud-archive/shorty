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
 * @file templates/tmpl_url_show.php
 * A read-only dialog visualizing all aspects of a selected shorty.
 * @access public
 * @author Christian Reiner
 */
?>

<!-- (hidden) dialog to show a shorty from the list -->
<form id="dialog-show" class="shorty-dialog shorty-standalone shorty-hidden">
	<fieldset>
		<legend class="">
			<a id="close" class="shorty-close-button"
				title="<?php p(OC_Shorty_L10n::t('Close')); ?>">
				<img alt="<?php p(OC_Shorty_L10n::t('Close')); ?>" class="svg"
					src="<?php p(OCP\Util::imagePath('shorty','actions/shade.svg'));  ?>">
			</a>
			<span class="heading"><?php p(OC_Shorty_L10n::t('Show details').':'); ?></span>
		</legend>
		<label for="source"><?php p(OC_Shorty_L10n::t('Source url').':'); ?></label>
		<input id="source" name="source" type="text" data="" class="" readonly="true" disabled />
		<br />
		<label for="relay"><?php p(OC_Shorty_L10n::t('Relay url').':'); ?></label>
		<input id="relay" name="relay" type="text" data="" class="" readonly="true" disabled />
		<br />
		<label for="target"><?php p(OC_Shorty_L10n::t('Target url').':'); ?></label>
		<input id="target" name="target" type="text" data="" class="" readonly="true" disabled />
		<br />
		<label for="meta"><img id="busy" height="12px" src="<?php p(OCP\Util::imagePath('shorty', 'loading-led.gif')); ?>"></label>
		<span id="meta" class="shorty-meta">
			<span class="">
				<img id="staticon"  class="shorty-icon svg" width="16px" data="blank"
					src="<?php p(OCP\Util::imagePath('shorty', 'blank.png')); ?>">
				<img id="schemicon" class="shorty-icon svg" width="16px" data="blank"
					src="<?php p(OCP\Util::imagePath('shorty', 'blank.png')); ?>">
				<img id="favicon"   class="shorty-icon svg" width="16px" data="blank"
					src="<?php p(OCP\Util::imagePath('shorty', 'blank.png')); ?>">
				<img id="mimicon"   class="shorty-icon svg" width="16px" data="blank"
					src="<?php p(OCP\Util::imagePath('shorty', 'blank.png')); ?>">
			</span>
			<span id="explanation" class="shorty-value" data=""></span>
		</span>
		<br />
		<label for="title"><?php p(OC_Shorty_L10n::t('Title').':'); ?></label>
		<input id="title" name="title" type="text" data="" class="" readonly="true" disabled />
		<br />
		<span class="label-line">
			<label for="status"><?php p(OC_Shorty_L10n::t('Status').':'); ?></label>
			<input id="status" name="status" type="text" data="" class="" style="width:8em;" readonly="true" disabled />
			<label for="until"><?php p(OC_Shorty_L10n::t('Expiration').':'); ?></label>
			<input id="until" name="until" type="text" data="" class="" style="width:12em;" readonly="true" disabled />
		</span>
		<br />
		<label for="notes"><?php p(OC_Shorty_L10n::t('Notes').':'); ?></label>
		<input id="notes" name="notes" data="" class="" readonly="true" disabled />
		<br />
		<span class="label-line">
			<label for="clicks"><?php p(OC_Shorty_L10n::t('Clicks').':'); ?></label>
			<input id="clicks" name="clicks" data="" type="textarea" class="" style="width:3em;" readonly="true" disabled />
			<label for="created"><?php p(OC_Shorty_L10n::t('Creation').':'); ?></label>
			<input id="created" name="created" type="text" data="" class="" style="width:7em;" readonly="true" disabled />
			<label for="accessed"><?php p(OC_Shorty_L10n::t('Access').':'); ?></label>
			<input id="accessed" name="accessed" type="text" data="" class="" style="width:11em;" readonly="true" disabled />
		</span>
  </fieldset>
</form>
