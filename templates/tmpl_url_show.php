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

<div id="dialog-show" class="shorty-dialog">
  <table>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Key'); ?></td>
        <td id="dialog_show_key" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Title'); ?></td>
        <td id="dialog_show_title" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Source url'); ?></td>
        <td id="dialog_show_source" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Target url'); ?></td>
        <td id="dialog_show_target" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Valid until'); ?></td>
        <td id="dialog_show_until" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Notes'); ?></td>
        <td id="dialog_show_notes" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Clicks'); ?></td>
        <td id="dialog_show_clicks" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Created'); ?></td>
        <td id="dialog_show_created" class="shorty-label"></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Accessed'); ?></td>
        <td id="dialog_show_accessed" class="shorty-label"></td></tr>
  </table>
</div>
