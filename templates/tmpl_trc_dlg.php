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
 * @file templates/tmpl_trc_dlg.php
 * A table to visualize the hit requests to existing Shortys
 * @access public
 * @author Christian Reiner
 */
?>

<!-- a (usually hidden) dialog used for verification of the correct setup of the 'static' backend -->
<div id="shorty-tracking-list-dialog"
     class="shorty-dialog shorty-standalone">
  <fieldset>
    <legend class="">
      <a id="close" class="shorty-close-button"
        title="<?php echo OC_ShortyTracking_L10n::t("close"); ?>">
        <img alt="<?php echo OC_ShortyTracking_L10n::t("close"); ?>"
            src="<?php echo OCP\Util::imagePath('shorty','actions/shade.png');  ?>">
      </a>
      <span id="slogan"><?php echo OC_ShortyTracking_L10n::t("List of tracked clicks");?></span><br>
    </legend>
    <!-- linguistic reference to the shorty -->
    <div id="shorty-reference">
      <span id="clicks" class="shorty-tracking-reference" data-slogan="<?php echo OC_ShortyTracking_L10n::t("Clicks");?>"></span>
      <span id="title" class="ellipsis shorty-tracking-reference" data-slogan="<?php echo OC_ShortyTracking_L10n::t("Title");?>"></span>
      <hr>
    </div>
      <table id="list">
        <!-- table header -->
        <thead>
          <tr id="titlebar">
            <th id="state"  >
              <span><img id="tools" alt="toolbar" title="<?php echo OC_ShortyTracking_L10n::t("Toggle toolbar");?>"
                        src="<?php echo OCP\Util::imagePath('shorty','actions/unshade.png'); ?>"
                        data-unshade="<?php echo OCP\Util::imagePath('shorty','actions/unshade.png'); ?>"
                        data-shade="<?php echo OCP\Util::imagePath('shorty','actions/shade.png'); ?>">
              </span>
            </th>
            <th id="result" ><span><?php echo OC_ShortyTracking_L10n::t("Result") ?></span></th>
            <th id="address"><span><?php echo OC_ShortyTracking_L10n::t("Address")?></span></th>
            <th id="host"   ><span><?php echo OC_ShortyTracking_L10n::t("Host")   ?></span></th>
            <th id="user"   ><span><?php echo OC_ShortyTracking_L10n::t("User")   ?></span></th>
            <th id="time"   ><span><?php echo OC_ShortyTracking_L10n::t("Time")   ?></span></th>
          </tr>
          <!-- table toolbar -->
          <tr id="toolbar">
            <th id="status">
              <div style="display:none;">
                <a id="reload"><img alt="<?php echo OC_ShortyTracking_L10n::t("reload"); ?>" title="<?php echo OC_ShortyTracking_L10n::t("Reload list"); ?>" src="<?php echo OCP\Util::imagePath('shorty','actions/reload.png'); ?>"></a>
              </div>
            </th>
            <th id="result">
              <div style="display:none;">
                <span class="shorty-select">
                  <select id='filter' value="" data-placeholder=" ">
                    <?php foreach($_['shorty-result'] as $option=>$label)
                      echo sprintf("<option value=\"%s\">%s</option>\n",($option?$option:''),$label);
                    ?>
                  </select>
                </span>
              </div>
            </th>
            <th id="address">
              <div style="display:none;">
                <input id='filter' type="text" value="">
              </div>
            </th>
            <th id="host">
              <div style="display:none;">
                <input id='filter' type="text" value="">
              </div>
            </th>
            <th id="user">
              <div style="display:none;">
                <input id='filter' type="text" value="">
              </div>
            </th>
            <th id="time">
              <div style="display:none;">
                <input id='filter' type="text" value="">
              </div>
            </th>
          </tr>
          <!-- the 'dummy' row, a blueprint -->
          <tr id="">
            <td id="status"></td>
            <td id="result"></td>
            <td id="address"></td>
            <td id="host"></td>
            <td id="user"></td>
            <td id="time"></td>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div id="footer">
        <img id="load" alt="load" title="<?php echo OC_ShortyTracking_L10n::t("load");?>"
             style="diaplay:none;" 
             src="<?php echo OCP\Util::imagePath('shorty','actions/unshade.png'); ?>">
      </div>
  </fieldset>
</div>
<!-- end of verification dialog -->
