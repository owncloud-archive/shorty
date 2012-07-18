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

/**
 * @file js/init.js
 * @brief Client side initialization of desktop actions
 * @author Christian Reiner
 */

$(document).ready(function(){
  // make notification closeable
  $('#content #notification').on('click',Shorty.WUI.Notification.hide);
  // button to open the 'add' dialog
  $('#controls #add').on('click',function(){Shorty.WUI.Dialog.toggle($('#dialog-add'))});
  // close button in dialogs
  $('.shorty-dialog #close').on('click',function(){Shorty.WUI.Dialog.hide($(this).parents('form').first());});
  // status selection in embedded share dialog
  $('.shorty-embedded#dialog-share #status').on('change',function(){
    Shorty.Action.Url.status($(this).siblings('#id').val(),$(this).val());
  });
  // collapsible items in embedded dialogs
  $('.shorty-embedded .shorty-collapsible span').on('click',function(e){
    var container=$(this).parent();
    if (container.hasClass('collapsed'))
         container.removeClass('collapsed').find('.shorty-collapsible-tail').slideDown('fast');
    else container.addClass('collapsed').find('.shorty-collapsible-tail').slideUp('fast');
  });
  // button (row click) to open the toolbar row in the list
  $('#list-of-shortys #titlebar').on('click',function(){Shorty.WUI.List.Toolbar.toggle($('#list-of-shortys'));});
  // button to reload the list
  $('#list-of-shortys #toolbar').find('#reload').on('click',Shorty.WUI.List.build);
  // sort buttons
  $('#list-of-shortys #toolbar').find('shorty-sorter').on('click',function(){Shorty.WUI.List.sort($('#list-of-shortys'));});
  // add date picker options
  $.datepicker.setDefaults({
    dateFormat :'yy-mm-dd',
    appendText: "(yyyy-mm-dd)",
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    showOn: 'button',
    buttonImage: $('#controls').find('#until').first().attr('icon'),
    buttonImageOnly: true
  });
  $('#controls #until:not([readonly])').datepicker();
  // bind usage to the usage icons
  $(document).on('click','#dialog-share img.shorty-usage',[],function(e){
    e.stopPropagation();
    Shorty.WUI.Entry.send(e,$(this));
  });
  $(document).on('click','.shorty-list tbody tr td:not(#actions)',[],function(e){
    // hide any open embedded dialog
    Shorty.WUI.Dialog.hide($('.shorty-embedded').first());
    // highlight clicked entry
    Shorty.WUI.List.highlight($(this).parents('table'),$(this).parent('tr'));
  });
  $(document).on('click','.shorty-list tbody tr td#actions a',[],function(e){
    Shorty.WUI.Entry.click(e,$(this));
  });
  // pretty select boxes where applicable (class controlled)
  $('.chosen').chosen();
  // filter actions
  var list=$('#list-of-shortys');
  // title & target filter reaction
  list.find('thead tr#toolbar').find('th#target,th#title').find('#filter').on('keyup',function(){
    Shorty.WUI.List.filter(
      list,
      $($(this).context.parentElement.parentElement).attr('id'),
      $(this).val()
    );
  });
  // status filter reaction
  list.find('thead tr#toolbar th#status select').change(function(){
    Shorty.WUI.List.filter(
      list,
      $(this).parents('th').attr('id'),
      $(this).find(':selected').val()
    );
  });
  // column sorting reaction
  list.find('thead tr#toolbar div img.shorty-sorter').on('click',function(){
    Shorty.WUI.List.sort(list,$(this).attr('data-sort-code'));
  });
}); // document.ready

