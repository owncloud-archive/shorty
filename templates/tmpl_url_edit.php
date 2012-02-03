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
<div id="dialog-edit" class="shorty-dialog">
  <form action="">
  <fieldset>
    <legend class="shorty-legend"><?php echo OC_Shorty_L10n::t('Modify shorty:'); ?></legend>
    <label for="dialog-edit-key"><?php echo OC_Shorty_L10n::t('Key'); ?></label>
    <input id="dialog-edit-key" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-title"><?php echo OC_Shorty_L10n::t('Title'); ?></label>
    <input id="dialog-edit-title" type="text" data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-source"><?php echo OC_Shorty_L10n::t('Shorty (url)'); ?></label>
    <input id="dialog-edit-source" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-target"><?php echo OC_Shorty_L10n::t('Target (url)'); ?></label>
    <input id="dialog-edit-target" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-until"><?php echo OC_Shorty_L10n::t('Valid until'); ?></label>
    <input id="dialog-edit-until" type="text" data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-notes"><?php echo OC_Shorty_L10n::t('Valid until'); ?></label>
    <textarea id="dialog-edit-notes" readonly cols="50" rows="3" data="" class="shorty-value"></textarea>
    <br>
    <label for="dialog-edit-clicks"><?php echo OC_Shorty_L10n::t('Clicks'); ?></label>
    <input id="dialog-edit-clicks" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-created"><?php echo OC_Shorty_L10n::t('Created'); ?></label>
    <input id="dialog-edit-created" type="text" readonly data="" class="shorty-value" />
    <br>
    <label for="dialog-edit-accessed"><?php echo OC_Shorty_L10n::t('Accessed'); ?></label>
    <input id="dialog-edit-accessed" type="text" readonly data="" class="shorty-value" />
    <br>
    <label colspan="2">&nbsp;</label>
    <input type="submit" value="<?php echo OC_Shorty_L10n::t('Save as modified'); ?>" id="dialog-add-submit" class="shorty-button-submit"/>
    <input type="submit" value="<?php echo OC_Shorty_L10n::t('Cancel'); ?>"     id="dialog-add-cancel" class="shorty-button-cancel"/></label>
  </fieldset>
  </form>
</div>
