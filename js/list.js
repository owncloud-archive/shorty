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

$(document).ready(function(){
  // basic action buttons
  $('#desktop').find('.shorty-actions').bind('hover',function(){$(this).fadeToggle();});
  // button to open the 'add' dialog
  $('#controls').find('#add').bind('click',function(){Shorty.WUI.Dialog.toggle($('#dialog-add'))});
  // button to open the tools header row in the list
  $('#list').find('#titlebar').bind('click',Shorty.WUI.List.Toolbar.toggle);
  // button to reload the list
  $('#list').find('#toolbar').find('#reload').bind('click',Shorty.WUI.List.build);
  // initialize desktop
  $.when(Shorty.WUI.Controls.init()).then(Shorty.WUI.List.build);
}); // document.ready
