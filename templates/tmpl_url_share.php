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
 * @file templates/tmpl_url_share.php
 * A dialog offering control over an entries state and offers the source url
 * @access public
 * @author Christian Reiner
 */
?>

<!-- (hidden) dialog to share a shorty from the list -->
<form id="dialog-share" class="shorty-dialog shorty-embedded">
  <fieldset>
    <legend class="">
      <a id="close" class="shorty-close-button"
        title="<?php echo OC_Shorty_L10n::t('close'); ?>">
        <img alt="<?php echo OC_Shorty_L10n::t('close'); ?>"
            src="<?php echo OC_Helper::imagePath('shorty','actions/shade.png');  ?>">
      </a>
      <?php echo OC_Shorty_L10n::t('Share shorty:'); ?>
    </legend>
    <input id="key" name="key" type="hidden" readonly data="" class="" readonly disabled />
    <label for="status"><?php echo OC_Shorty_L10n::t('Status:'); ?></label>
    <select id="status" name="status" data="" class="" value="">
      <option value="blocked">
        <?php echo OC_Shorty_L10n::t('blocked'); ?>
      </option>
      <option value="shared" >
        <?php echo OC_Shorty_L10n::t('shared');  ?>
      </option>
      <option value="public" >
        <?php echo OC_Shorty_L10n::t('public');  ?>
      </option>
    </select>
    <br />
    <label for="source"><?php echo OC_Shorty_L10n::t('Source url:'); ?></label>
    <a id="source" class="shorty-clickable" target="_blank"
       title="<?php echo OC_Shorty_L10n::t('open source url'); ?>"
       href=""></a>
    <br />
    <label for="relay"><?php echo OC_Shorty_L10n::t('Relay url:'); ?></label>
    <a id="relay" class="shorty-clickable" target="_blank"
       title="<?php echo OC_Shorty_L10n::t('open relay url'); ?>"
       href=""></a>
    <br />
    <label for="target"><?php echo OC_Shorty_L10n::t('Target url:'); ?></label>
    <a id="target" class="shorty-clickable" target="_blank"
       title="<?php echo OC_Shorty_L10n::t('open target url'); ?>"
       href=""></a>
  </fieldset>
</form>
