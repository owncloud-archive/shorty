<?php
/**
* @package shorty-tracking an ownCloud url shortener plugin addition
* @category internet
* @author Christian Reiner
* @copyright 2012-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty-tracking
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
 * @file templates/tmpl_trc_dlg_click.php
 * A table to visualize the hit requests to existing Shortys
 * @access public
 * @author Christian Reiner
 */
?>

<!-- begin of click details dialog -->
<form id="shorty-tracking-click-dialog" class="shorty-dialog shorty-embedded">
  <fieldset class="">
    <legend class="">
      <a id="close" class="shorty-close-button"
        title="<?php echo OC_Shorty_L10n::t("Close"); ?>">
        <img alt="<?php echo OC_Shorty_L10n::t("Close"); ?>"
            src="<?php echo OCP\Util::imagePath('apps/shorty','actions/shade.png');  ?>">
      </a>
      <?php echo OC_ShortyTracking_L10n::t("Click details").':'; ?>
    </legend>
    <label for="shorty-title"><?php echo OC_ShortyTracking_L10n::t("Title");?>: </label>
    <span id="shorty-title" class="ellipsis"></span>
    <hr>
    <label for="click-result"><?php echo OC_ShortyTracking_L10n::t("Result");?>: </label>
    <span id="click-result"></span>
    <br />
    <label for="click-address"><?php echo OC_ShortyTracking_L10n::t("Address");?>: </label>
    <span id="click-address"></span>
    <br />
    <label for="click-host"><?php echo OC_ShortyTracking_L10n::t("Host");?>: </label>
    <span id="click-host"></span>
    <br />
    <label for="click-user"><?php echo OC_ShortyTracking_L10n::t("User");?>: </label>
    <span id="click-user"></span>
    <br />
    <label for="click-time"><?php echo OC_ShortyTracking_L10n::t("Time");?>: </label>
    <span id="click-time"></span>
  </fieldset>
</form>
<!-- end of click tracking details dialog -->
