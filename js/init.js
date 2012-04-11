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

/**
 * @file js/init.js
 * @brief Client side initialization of desktop actionss
 * @author Christian Reiner
 */

/**
 * @brief General initialization of desktop elements
 * @author Christian Reiner
 */
$(document).ready(function(){
  // make notification closeable
  $('#content #notification').bind('click',Shorty.WUI.Notification.hide);
  // button to open the 'add' dialog
  $('#controls #add').bind('click',function(){Shorty.WUI.Dialog.toggle($('#dialog-add'))});
  // close button in embedded dialogs
  $('.shorty-embedded #close').bind('click',function(){Shorty.WUI.Dialog.hide($(this).parents('form').eq(0));});
  // button to open the tools header row in the list
  $('#list #titlebar').bind('click',Shorty.WUI.List.Toolbar.toggle);
  // button to reload the list
  $('#list #toolbar').find('#reload').bind('click',Shorty.WUI.List.build);
  // sort buttons
  $('#list #toolbar').find('shorty-sorter').bind('click',Shorty.WUI.List.sort);
  // add date picker options
  $('#controls #until').datepicker({
    dateFormat :'dd-mm-yy',
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    showOn: 'button',
    buttonImage: $('#controls').find('#until').eq(0).attr('icon'),
    buttonImageOnly: true
  });
  // bind actions to the actions icons
  $('#list .shorty-actions a').live('click',function(e){Shorty.WUI.Entry.click(e,$(this));});
  // pretty select boxes throughout this module
  $('.shorty-dialog select').chosen();
}); // document.ready
