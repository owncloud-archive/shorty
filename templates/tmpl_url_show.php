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

<!-- (hidden) dialog to show a shorty from the list -->
<form id="dialog-show" class="shorty-dialog">
  <fieldset>
    <legend class="shorty-legend"><?php echo OC_Shorty_L10n::t('Modify shorty:'); ?></legend>
    <label for="key"><?php echo OC_Shorty_L10n::t('Shorty-Key:'); ?></label>
    <input id="key" type="text" readonly data="" class="" />
    <br>
    <label for="source"><?php echo OC_Shorty_L10n::t('Shorty (url):'); ?></label>
    <input id="source" type="text" readonly data="" class="" />
    <br>
    <label for="target"><?php echo OC_Shorty_L10n::t('Target (url):'); ?></label>
    <input id="target" type="text" readonly data="" class="" />
    <br>
    <label for="title"><?php echo OC_Shorty_L10n::t('Local Title:'); ?></label>
    <input id="title" type="text" readonly data="" class="" />
    <br>
    <label for="until"><?php echo OC_Shorty_L10n::t('Valid until:'); ?></label>
    <input id="until" type="text" readonly data="" class="" />
    <br>
    <label for="notes"><?php echo OC_Shorty_L10n::t('Notes:'); ?></label>
    <textarea id="notes" readonly data="" class=""></textarea>
    <br>
    <label for="clicks"><?php echo OC_Shorty_L10n::t('Clicks:'); ?></label>
    <input id="clicks" type="text" readonly data="" class="" />
    <br>
    <label for="created"><?php echo OC_Shorty_L10n::t('Created:'); ?></label>
    <input id="created" type="text" readonly data="" class="" />
    <br>
    <label for="accessed"><?php echo OC_Shorty_L10n::t('Accessed:'); ?></label>
    <input id="accessed" type="text" readonly data="" class="" />
  </fieldset>
</form>
