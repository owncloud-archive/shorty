<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011 Christian Reiner <foss@christian-reiner.info>
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

<div id="dialog_edit" class="shorty-dialog">
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Key'); ?></label>
     <label id="dialog_show_target" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Source url'); ?></label>
     <label id="dialog_show_target" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Target url'); ?></label>
     <input type="text" id="dialog_show_target" class="shorty-label"/></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Notes'); ?></label>
     <textarea cols=50 rows=3 id="dialog_show_notes" class="shorty-label"></textarea></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Valid until'); ?></label>
     <input type="text" id="dialog_show_until" class="shorty-label"/></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Clicks'); ?></label>
     <label id="dialog_show_clicks" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Created'); ?></label>
     <label id="dialog_show_created" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Accessed'); ?></label>
     <label id="dialog_show_accessed" class="shorty-label"></label></p>
  <p><label class="shorty-label"></label>
     <input type="submit" value="<?php echo OC_Shorty_L10n::t('Save modified'); ?>" id="dialog_edit_submit" /></p>
</div>
