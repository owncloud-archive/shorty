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

<!-- (hidden) dialog to add a new shorty -->
<div id="dialog-add" class="shorty-dialog">
  <form action="">
  <fieldset>
    <legend class="shorty-legend"><?php echo OC_Shorty_L10n::t('Add a new shorty:'); ?></legend>
    <label for="dialog-add-target"><?php echo OC_Shorty_L10n::t('Target (url)'); ?></label>
    <img class="shorty-favicon" src="<?php echo OC_Helper::imagePath('shorty', 'blank.gif'); ?>">
    <img class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'loading.gif'); ?>">
    <input type="text" size="50" maxsize="4096" id="dialog-add-target" data="" class="shorty-input" />
    <br>
    <label for="dialog-add-favicon"><?php echo OC_Shorty_L10n::t('Entry title'); ?></label>
    <img class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'loading.gif'); ?>">
    <img id="dialog-add-favicon" class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'loading.gif'); ?>">
    <input type="text" size="50" maxsize="80" id="dialog-add-title" data="" class="shorty-input"/>
    <br>
    <label for="dialog-add-until"><?php echo OC_Shorty_L10n::t('Valid until'); ?></label>
    <input type="text" size="50" maxsize="50" id="dialog-add-until" data="" class="shorty-input datepicker" />
    <br>
    <label for="dialog-add-notes"><?php echo OC_Shorty_L10n::t('Notes'); ?></label>
    <textarea cols="50" rows="3" maxsize="4096" id="dialog-add-notes" data="" class="shorty-input"></textarea>
    <br>
    <label colspan="2">&nbsp;</label>
    <input type="submit" value="<?php echo OC_Shorty_L10n::t('Add as new'); ?>" id="dialog-add-submit" class="shorty-button-submit"/>
    <input type="submit" value="<?php echo OC_Shorty_L10n::t('Cancel'); ?>"     id="dialog-add-cancel" class="shorty-button-cancel"/></label>
  </fieldset>
  </form>
</div>
