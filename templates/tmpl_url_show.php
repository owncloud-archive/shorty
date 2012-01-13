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

<div id="dialog_show" class="shorty-dialog">
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Key'); ?></label>
     <label id="dialog_show_target" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Source url'); ?></label>
     <label id="dialog_show_target" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Target url'); ?></label>
     <label id="dialog_show_target" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Notes'); ?></label>
     <label id="dialog_show_notes" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Valid until'); ?></label>
     <label id="dialog_show_until" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Clicks'); ?></label>
     <label id="dialog_show_clicks" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Created'); ?></label>
     <label id="dialog_show_created" class="shorty-label"></label></p>
  <p><label class="shorty-label"><?php echo OC_Shorty_L10n::t('Accessed'); ?></label>
     <label id="dialog_show_accessed" class="shorty-label"></label></p>
</div>
