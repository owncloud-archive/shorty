<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information 
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty
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
 * @file templates/tmpl_url_edit.php
 * A dialog to modify some aspects of a selected shorty.
 * @access public
 * @author Christian Reiner
 */
?>

<!-- (hidden) dialog to modify a stored shorty -->
<form id="dialog-edit" class="shorty-dialog shorty-standalone">
  <fieldset>
    <legend class="shorty-legend"><?php echo OC_Shorty_L10n::t('Modify shorty:'); ?></legend>
    <label for="key"><?php echo OC_Shorty_L10n::t('Shorty-Key:'); ?></label>
    <input id="key" name="key" type="text" readonly data="" class="" />
    <br>
    <label for="source"><?php echo OC_Shorty_L10n::t('Shorty (url):'); ?></label>
    <input id="source" name="source" type="text" readonly data="" class="" />
    <br>
    <label for="target"><?php echo OC_Shorty_L10n::t('Target (url):'); ?></label>
    <input id="target" name="target" type="text" readonly data="" class="" />
    <br>
    <label for="meta">&nbsp;</label>
    <span id="meta">
    <img id="staticon"  class="shorty-icon" src="" data="<?php echo OC_Helper::imagePath('shorty', 'status/neutral.png'); ?>">
    <img id="schemicon" class="shorty-icon" src="" data="<?php echo OC_Helper::imagePath('shorty', 'blank.png'); ?>">
    <img id="favicon"   class="shorty-icon" src="" data="<?php echo OC_Helper::imagePath('shorty', 'blank.png'); ?>">
    <img id="mimicon"   class="shorty-icon" src="" data="<?php echo OC_Helper::imagePath('shorty', 'blank.png'); ?>">
    <a id="explanation" maxlength="80" data="" class="shorty-value"></a>
    </span>
    <br>
    <label for="title"><?php echo OC_Shorty_L10n::t('Local title:'); ?></label>
    <input id="title" name="title" type="text" maxlength="80" data="" class="" />
    <br>
    <label for="until"><?php echo OC_Shorty_L10n::t('Valid until:'); ?></label>
    <input id="until" name="until" type="text" maxlength="10" data="" class="datepicker" style="width:30%"
           icon="<?php echo OC_Helper::imagePath('shorty', 'calendar.png'); ?>"/>
    <br>
    <label for="notes"><?php echo OC_Shorty_L10n::t('Notes:'); ?></label>
    <textarea id="notes" name="notes" maxlength="4096" data="" class=""></textarea>
    <br>
    <label for="clicks"><?php echo OC_Shorty_L10n::t('Clicks:'); ?></label>
    <input id="clicks" name="clicks" type="text" readonly data="" class="" />
    <br>
    <label for="created"><?php echo OC_Shorty_L10n::t('Created:'); ?></label>
    <input id="created" name="created" type="text" readonly data="" class="" />
    <br>
    <label for="accessed"><?php echo OC_Shorty_L10n::t('Accessed:'); ?></label>
    <input id="accessed" name="accessed" type="text" readonly data="" class="" />
    <br>
    <label for="confirm"></label>
    <input type="submit" value="<?php echo OC_Shorty_L10n::t('Save as modified'); ?>" id="confirm" class="shorty-button-submit"/>
  </fieldset>
</form>
