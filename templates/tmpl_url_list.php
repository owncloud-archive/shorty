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

<div class="shorty-hourglass"><img src="<?php echo OC_Helper::imagePath('shorty', 'loading-disk.gif'); ?>" style=""></div>

<!-- the placeholder (if list of urls is empty) -->
<table id="list-empty" class="shorty-list" style="display:none;">
  <thead>
    <tr>
      <th id="headerFavicon"><?php echo OC_Shorty_L10n::t('') ?></th>
      <th id="headerTitle"  ><?php echo OC_Shorty_L10n::t('Title') ?></th>
      <th id="headerTarget" ><?php echo OC_Shorty_L10n::t('Target') ?></th>
      <th id="headerClicks" ><?php echo OC_Shorty_L10n::t('Clicks') ?></th>
      <th id="headerUntil"  ><?php echo OC_Shorty_L10n::t('Until') ?></th>
      <th id="headerAction" ><?php echo OC_Shorty_L10n::t('Actions') ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="7" class="shorty-label" style="font-style:italic;text-align:center;"><?php echo OC_Shorty_L10n::t('List currently empty.') ?></td>
    </tr>
  </tbody>
</table>
<!-- the list of urls -->
<table id="list-nonempty" class="shorty-list" style="display:none;">
  <thead>
    <tr>
      <th id="headerFavicon"><?php echo OC_Shorty_L10n::t('') ?></th>
      <th id="headerTitle"  ><?php echo OC_Shorty_L10n::t('Title') ?></th>
      <th id="headerTarget" ><?php echo OC_Shorty_L10n::t('Target') ?></th>
      <th id="headerClicks" ><?php echo OC_Shorty_L10n::t('Clicks') ?></th>
      <th id="headerUntil"  ><?php echo OC_Shorty_L10n::t('Until') ?></th>
      <th id="headerAction" ><?php echo OC_Shorty_L10n::t('Actions') ?></th>
    </tr>
  </thead>
  <tbody>
    <tr id=""
        data-key=""
        data-source=""
        data-title=""
        data-favicon=""
        data-target=""
        data-clicks=""
        data-until=""
        data-created=""
        data-accessed=""
        data-notes=""
        style="hidden" >
      <td id="favicon"></td>
      <td id="title"  ></td>
      <td id="target" ></td>
      <td id="clicks" ></td>
      <td id="until"  ></td>
      <td id="actions">
        <div class="shorty-actions">
          <a href="" title="Download" class="download"><img class="svg" alt="Download" src="/owncloud/core/img/actions/download.svg" /></a>
          <a href="" title="Share" class="share"><img class="svg" alt="Share" src="/owncloud/core/img/actions/share.svg" /></a>
          <a href="" title="Delete" class="delete"><img class="svg" alt="Delete" src="/owncloud/core/img/actions/delete.svg" /></a>
        </div>
      </td>
    </tr>
  </tbody>
</table>
