<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401 
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
 * @file templates/tmpl_url_list.php
 * A table to visualize the list of existing shortys.
 * @access public
 * @author Christian Reiner
 */
?>

<!-- the 'hourglass', a general busy indicator -->
<div id="hourglass" class="shorty-hourglass" style="left:10em;top:10em;"><img src="<?php echo OCP\Util::imagePath('shorty', 'loading-disk.gif'); ?>"></div>

<!-- the list of urls, empty variant -->
<div id="vacuum" class="shorty-vacuum personalblock">
  <div class="factoid"><?php echo OC_Shorty_L10n::t("Nothing here yet")." !" ?></div>
  <div class="suggestion"><?php echo OC_Shorty_L10n::t("Create a new 'Shorty' and share it").":" ?></div>
  <div class="explanation"><?php echo OC_Shorty_L10n::t("Use the '%s' button above or the fine 'Shortlet'",OC_Shorty_L10n::t("New Shorty")).":" ?></div>
<?php require_once('tmpl_wdg_shortlet.php'); ?>
</div>

<!-- the list of urls, non-empty variant -->
<table id="list" class="shorty-list" style="display:none;">
  <thead>
    <tr id="titlebar">
      <!-- a button to open/close the toolbar below -->
      <th id="favicon"><span><img id="tools" alt="toolbar" title="<?php echo OC_Shorty_L10n::t('Toggle toolbar');?>"
                                  src="<?php echo OCP\Util::imagePath('shorty','actions/unshade.png'); ?>"
                                  data-unshade="<?php echo OCP\Util::imagePath('shorty','actions/unshade.png'); ?>"
                                  data-shade="<?php echo OCP\Util::imagePath('shorty','actions/shade.png'); ?>"></span></th>
      <th id="title"  ><span><?php echo OC_Shorty_L10n::t('Title')      ?></span></th>
      <th id="target" ><span><?php echo OC_Shorty_L10n::t('Target')     ?></span></th>
      <th id="clicks" ><span><?php echo OC_Shorty_L10n::t('Clicks')     ?></span></th>
      <th id="until"  ><span><?php echo OC_Shorty_L10n::t('Expiration') ?></span></th>
      <th id="status" ><span><?php echo OC_Shorty_L10n::t('Status')     ?></span></th>
      <th id="action" ><span>&nbsp;</span></th>
    </tr>
    <!-- toolbar opened/closed by the button above -->
    <tr id="toolbar">
      <th id="favicon">
        <div style="display:none;">
          <a id="reload"><img alt="<?php echo OC_Shorty_L10n::t('reload'); ?>" title="<?php echo OC_Shorty_L10n::t('Reload list'); ?>" src="<?php echo OCP\Util::imagePath('shorty','actions/reload.png'); ?>"></a>
        </div>
      </th>
      <th id="title">
        <div style="display:none;">
          <img id="sort-up" class="shorty-sorter" data-sort-code="ta" data-sort-type="string" data-sort-direction='asc' 
               alt="<?php echo OC_Shorty_L10n::t('up');   ?>" title="<?php echo OC_Shorty_L10n::t('Sort ascending');  ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
          <img id="sort-down" class="shorty-sorter" data-sort-code="td" data-sort-type="string" data-sort-direction='desc' 
               alt="<?php echo OC_Shorty_L10n::t('down'); ?>" title="<?php echo OC_Shorty_L10n::t('Sort descending'); ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
          <input id='filter' type="text" value="">
        </div>
      </th>
      <th id="target">
        <div style="display:none;">
          <img id="sort-up" class="shorty-sorter" data-sort-code="ua" data-sort-type="string" data-sort-direction='asc'
               alt="<?php echo OC_Shorty_L10n::t('up');   ?>" title="<?php echo OC_Shorty_L10n::t('Sort ascending');  ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
          <img id="sort-down" class="shorty-sorter" data-sort-code="ud" data-sort-type="string" data-sort-direction='desc'
               alt="<?php echo OC_Shorty_L10n::t('down'); ?>" title="<?php echo OC_Shorty_L10n::t('Sort descending'); ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
          <input id='filter' type="text" value="">
        </div>
      </th>
      <th id="clicks">
        <div style="display:none;">
          <img id="sort-up"   class="shorty-sorter" data-sort-code="ha" data-sort-type="int" data-sort-direction='asc'
               alt="<?php echo OC_Shorty_L10n::t('up');   ?>" title="<?php echo OC_Shorty_L10n::t('Sort ascending');  ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
          <img id="sort-down" class="shorty-sorter" data-sort-code="hd" data-sort-type="int" data-sort-direction='desc'
               alt="<?php echo OC_Shorty_L10n::t('down'); ?>" title="<?php echo OC_Shorty_L10n::t('Sort descending'); ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
        </div>
      </th>
      <th id="until">
        <div style="display:none;">
          <img id="sort-up"   class="shorty-sorter" data-sort-code="da" data-sort-type="date" data-sort-direction='asc'
               alt="<?php echo OC_Shorty_L10n::t('up');   ?>" title="<?php echo OC_Shorty_L10n::t('Sort ascending');  ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/up.png');   ?>">
          <img id="sort-down" class="shorty-sorter" data-sort-code="dd" data-sort-type="date" data-sort-direction='desc'
               alt="<?php echo OC_Shorty_L10n::t('down'); ?>" title="<?php echo OC_Shorty_L10n::t('Sort descending'); ?>"
               src="<?php echo OCP\Util::imagePath('shorty','actions/down.png'); ?>">
        </div>
      </th>
      <!-- status filter, colspan 2 to prevent width enhancement of column -->
      <th id="status" colspan=2>
        <div style="display:none;">
          <span class="shorty-select">
            <select id='filter' value="" data-placeholder=" ">
              <?php foreach($_['shorty-status'] as $option=>$label)
                echo sprintf("<option value=\"%s\">%s</option>\n",($option?$option:''),$label);
              ?>
            </select>
          </span>
        </div>
      </th>
    </tr>
    <!-- the 'dummy' row, a blueprint -->
    <tr id=""
        data-id=""
        data-status=""
        data-source=""
        data-relay=""
        data-title=""
        data-favicon=""
        data-target=""
        data-clicks=""
        data-until=""
        data-created=""
        data-accessed=""
        data-notes="">
      <td id="favicon"></td>
      <td id="title"  ></td>
      <td id="target" ></td>
      <td id="clicks" ></td>
      <td id="until"  ></td>
      <td id="status" ></td>
      <td id="actions">
        <span class="shorty-actions">
<!-- IF any additional actions are registered via hooks, additional icons will appear here -->
<?php foreach ( $_['shorty-actions']['list'] as $action ) { ?>
          <a id="<?php echo $action['id'] ?>" title="<?php echo array_key_exists('title',$action)?$action['title']:''?>"
             data_method="<?php echo $action['call'] ?>" class="">
            <img class="shorty-icon"
                 alt="<?php echo array_key_exists('alt',$action)?$action['alt']:''?>"
                 title="<?php echo array_key_exists('title',$action)?$action['title']:''?>"
                 src="<?php echo $action['icon']?>" />
          </a>
<?php } ?>
          <a id="show"   title="<?php echo OC_Shorty_L10n::t('show');   ?>"   class="">
            <img class="shorty-icon" alt="<?php echo OC_Shorty_L10n::t('show'); ?>"   title="<?php echo OC_Shorty_L10n::t('Show details'); ?>"
                 src="<?php echo OCP\Util::imagePath('shorty','actions/info.png');   ?>" />
          </a>
          <a id="edit"   title="<?php echo OC_Shorty_L10n::t('edit');   ?>"   class="">
            <img class="shorty-icon" alt="<?php echo OC_Shorty_L10n::t('modify'); ?>"   title="<?php echo OC_Shorty_L10n::t('Modify shorty'); ?>"
                 src="<?php echo OCP\Util::imagePath('core','actions/rename.png'); ?>" />
          </a>
          <a id="del"    title="<?php echo OC_Shorty_L10n::t('delete'); ?>" class="">
            <img class="shorty-icon" alt="<?php echo OC_Shorty_L10n::t('delete'); ?>" title="<?php echo OC_Shorty_L10n::t('Delete shorty'); ?>"
                 src="<?php echo OCP\Util::imagePath('core','actions/delete.png'); ?>" />
          </a>
          <a id="share"  title="<?php echo OC_Shorty_L10n::t('share');  ?>"   class="">
            <img class="shorty-icon" alt="<?php echo OC_Shorty_L10n::t('share'); ?>"  title="<?php echo OC_Shorty_L10n::t('Share shorty'); ?>"
                 src="<?php echo OCP\Util::imagePath('core','actions/share.png');  ?>" />
          </a>
          <a id="open"   title="<?php echo OC_Shorty_L10n::t('open');   ?>"   class="">
            <img class="shorty-icon" alt="<?php echo OC_Shorty_L10n::t('open'); ?>"   title="<?php echo OC_Shorty_L10n::t('Open target'); ?>"
                 src="<?php echo OCP\Util::imagePath('shorty','actions/open.png'); ?>" />
          </a>
        </span>
      </td>
    </tr>
  </thead>
  <!-- the standard body for non-empty lists -->
  <tbody>
  </tbody>
</table>
