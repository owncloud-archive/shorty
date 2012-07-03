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
        title="<?php echo OC_Shorty_L10n::t('Close'); ?>">
        <img alt="<?php echo OC_Shorty_L10n::t('Close'); ?>"
            src="<?php echo OCP\Util::imagePath('shorty','actions/shade.png');  ?>">
      </a>
      <?php echo $l->t("List of tracked clicks"); ?>
    </legend>
    <table id="shorty-tracking-list-table">
      <!-- table header -->
      <thead>
        <tr>
          <th id="state"  >
            <span><img id="shorty-tracking-list-tools" alt="toolbar" title="<?php echo $l->t('Toggle toolbar');?>"
                      src="<?php echo OCP\Util::imagePath('shorty','actions/plus.png'); ?>"
                      data-plus="<?php echo OCP\Util::imagePath('shorty','actions/plus.png'); ?>"
                      data-minus="<?php echo OCP\Util::imagePath('shorty','actions/minus.png'); ?>">
            </span>
          </th>
          <th id="time"   ><span><?php echo OC_Shorty_L10n::t('Time')   ?></span></th>
          <th id="address"><span><?php echo OC_Shorty_L10n::t('Address')?></span></th>
          <th id="domain" ><span><?php echo OC_Shorty_L10n::t('Domain') ?></span></th>
          <th id="user"   ><span><?php echo OC_Shorty_L10n::t('User')   ?></span></th>
          <th id="result" ><span><?php echo OC_Shorty_L10n::t('result') ?></span></th>
        </tr>
        <!-- table toolbar -->
        <tr id="shorty-tracking-list-toolbar">
          <th id="status">
            <div style="display:none;">
              <a id="reload"><img alt="<?php echo $l->t('reload'); ?>" title="<?php echo $l->t('Reload list'); ?>" src="<?php echo OCP\Util::imagePath('shorty','actions/reload.png'); ?>"></a>
            </div>
          </th>
          <th id="time">
            <div style="display:none;">
              <img id="sort-up" class="shorty-sorter" data-sort-code="ta" data-sort-type="string" data-sort-direction='asc'
                  alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('Sort ascending');  ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
              <img id="sort-down" class="shorty-sorter" data-sort-code="td" data-sort-type="string" data-sort-direction='desc'
                  alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('Sort descending'); ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
              <input id='filter' type="text" value="">
            </div>
          </th>
          <th id="address">
            <div style="display:none;">
              <img id="sort-up" class="shorty-sorter" data-sort-code="ua" data-sort-type="string" data-sort-direction='asc'
                  alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('Sort ascending');  ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
              <img id="sort-down" class="shorty-sorter" data-sort-code="ud" data-sort-type="string" data-sort-direction='desc'
                  alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('Sort descending'); ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
              <input id='filter' type="text" value="">
            </div>
          </th>
          <th id="domain">
            <div style="display:none;">
              <img id="sort-up" class="shorty-sorter" data-sort-code="ua" data-sort-type="string" data-sort-direction='asc'
                  alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('Sort ascending');  ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
              <img id="sort-down" class="shorty-sorter" data-sort-code="ud" data-sort-type="string" data-sort-direction='desc'
                  alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('Sort descending'); ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
              <input id='filter' type="text" value="">
            </div>
          </th>
          <th id="user">
            <div style="display:none;">
              <img id="sort-up" class="shorty-sorter" data-sort-code="ua" data-sort-type="string" data-sort-direction='asc'
                  alt="<?php echo $l->t('up');   ?>" title="<?php echo $l->t('Sort ascending');  ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
              <img id="sort-down" class="shorty-sorter" data-sort-code="ud" data-sort-type="string" data-sort-direction='desc'
                  alt="<?php echo $l->t('down'); ?>" title="<?php echo $l->t('Sort descending'); ?>"
                  src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
              <input id='filter' type="text" value="">
            </div>
          </th>
          <!-- status filter, colspan 2 to prevent width enhancement of column -->
          <th id="result" colspan=2>
            <div style="display:none;">
              <span id="horst" class="shorty-select">
                <select id='filter' value="" data-placeholder=" ">
                  <?php foreach($_['shorty-result'] as $result=>$label)
                    echo sprintf("<option value=\"%s\">%s</option>\n",($status?$label:''),$label);
                  ?>
                </select>
              </span>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr id="">
          <td id="status"></td>
          <td id="time"></td>
          <td id="address"></td>
          <td id="domain"></td>
          <td id="user"></td>
          <td id="result"></td>
        </tr>
      </tbody>
    </table>
  </fieldset>
</div>
<!-- end of verification dialog -->
