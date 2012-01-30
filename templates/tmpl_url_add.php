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

<div id="dialog-add" class="shorty-dialog">
  <table>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Target (url)'); ?></td>
        <td class="shorty-input"><img class="shorty-favicon" src="<?php echo OC_Helper::imagePath('shorty', 'blank.gif'); ?>">
                                 <img class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'loading.gif'); ?>">
                                 <input type="text" id="dialog-add-target" data="" class="shorty-input" /></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Entry title'); ?></td>
        <td class="shorty-input"><img class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'loading.gif'); ?>">
                                 <img id="dialog-add-favicon" class="shorty-favicon" src="<?php echo OC_Helper::imagePath('core', 'loading.gif'); ?>">
                                 <input type="text" id="dialog-add-title" data="" class="shorty-input"/></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Valid until'); ?></td>
        <td class="shorty-input"><input type="text" id="dialog-add-until" data="" class="shorty-input datepicker" /></td></tr>
    <tr><td class="shorty-label"><?php echo OC_Shorty_L10n::t('Notes'); ?></td>
        <td class="shorty-input"><textarea cols=50 rows=3 id="dialog-add-notes" data="" class="shorty-input"></textarea></td></tr>
    <tr><td class="shorty-label"></td>
        <td class="shorty-input"><input type="submit" value="<?php echo OC_Shorty_L10n::t('Add as new'); ?>" id="dialog-add-submit" /></td><tr>
  </table>
</div>
