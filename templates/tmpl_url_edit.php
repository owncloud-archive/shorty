<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
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

<!-- (hidden) dialog to modify a stored shorty -->
<form id="dialog-edit" class="shorty-dialog">
  <fieldset>
    <legend class="shorty-legend"><?php echo OC_Shorty_L10n::t('Modify shorty:'); ?></legend>
    <label for="key"><?php echo OC_Shorty_L10n::t('Shorty-Key:'); ?></label>
    <input id="key" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="title"><?php echo OC_Shorty_L10n::t('Local title:'); ?></label>
    <input id="title" type="text" data="" class="shorty-value" />
    <br>
    <label for="source"><?php echo OC_Shorty_L10n::t('Shorty (url):'); ?></label>
    <input id="source" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="target"><?php echo OC_Shorty_L10n::t('Target (url):'); ?></label>
    <input id="target" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="favicon"></label>
    <img id="favicon" class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'blank.gif'); ?>">
    <a id="mimetype" type="text" readonly size="50" maxsize="80" data="" class="shorty-input"/>
    <label for="until"><?php echo OC_Shorty_L10n::t('Valid until:'); ?></label>
    <input id="until" type="text" data="" class="shorty-value" />
    <br>
    <label for="notes"><?php echo OC_Shorty_L10n::t('Notes:'); ?></label>
    <textarea id="notes" readonly cols="50" rows="3" data="" class="shorty-value"></textarea>
    <br>
    <label for="clicks"><?php echo OC_Shorty_L10n::t('Clicks:'); ?></label>
    <input id="clicks" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="created"><?php echo OC_Shorty_L10n::t('Created:'); ?></label>
    <input id="created" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="accessed"><?php echo OC_Shorty_L10n::t('Accessed:'); ?></label>
    <input id="accessed" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="confirm"></label>
    <input type="submit" value="<?php echo OC_Shorty_L10n::t('Save as modified'); ?>" id="confirm" class="shorty-button-submit"/>
  </fieldset>
</form>
