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

/**
 * @file js/tracking.js
 * @brief Client side initialization of desktop actions
 * @author Christian Reiner
 */

$(document).ready(function(){
  // load layout of dialog to show the list of tracked clicks
  Shorty.Tracking.init();
}); // document.ready

Shorty.Tracking=
{
  // Shorty.Tracking.control
  control:function(entry){
    if (Shorty.Debug) Shorty.Debug.log("tracking list controller");
    var dfd=new $.Deferred();
    var dialog=$('#controls #shorty-tracking-list-dialog');
    $.when(
    ).done(function(){
      Shorty.WUI.Dialog.show(dialog)
      dfd.resolve();
    }).fail(function(){
      dfd.reject();
    })
    return dfd.promise();
  }, // Shorty.Tracking.control
  // Shorty.Tracking.dialog
  dialog:{},
  // Shorty.Tracking.init
  init:function(){
    if (Shorty.Debug) Shorty.Debug.log("initializing tracking list");
    var dfd=new $.Deferred();
    // does the dialog holding the list exist already ?
    if (!$.isEmptyObject(Shorty.Tracking.dialog)){
      // remove all (real) entries so that the table can be filled again
      $('#shorty-tracking-list-dialog #shorty-tracking-list-table tr:not(#)').remove();
      dfd.resolve();
    }else{
      // load dialog layout via ajax and create a freshly populated dialog
      $.ajax({
        type:     'GET',
        url:      OC.filePath('shorty-tracking','ajax','layout.php'),
        cache:    false,
        dataType: 'json'
      }).pipe(
        function(response){return Shorty.Ajax.eval(response)},
        function(response){return Shorty.Ajax.fail(response)}
      ).done(function(response){
        // create a fresh dialog and insert it alongside theesting dialogs in the top controls bar
        $('#controls').append(response.layout);
        // keep that new dialog for alter usage and control
        Shorty.Tracking.dialog=$('#controls #shorty-tracking-list-dialog');
        // bind actions to basic buttons
        Shorty.Tracking.dialog.find('#close').bind('click',function(){Shorty.WUI.Dialog.hide(Shorty.Tracking.dialog);});
        dfd.resolve(response);
      }).fail(function(response){
        dfd.reject(response);
      })
    } // else
    return dfd.promise();
  },
  // Shorty.Tracking.list
  list:function(id,offset){
    if (Shorty.Debug) Shorty.Debug.log("loading clicks into tracking list");
    var dfd=new $.Deferred();
    // retrieve list template
    var data={
      id:    id,
      offset:offset};
    $.ajax({
      type:     'GET',
      url:      OC.filePath('shorty-tracking','ajax','list.php'),
      cache:    false,
      data:     data,
      dataType: 'json'
    }).pipe(
      function(response){return Shorty.Ajax.eval(response)},
      function(response){return Shorty.Ajax.fail(response)}
    ).done(function(response){
      $(list).dialog();

    // wipe entries in dialog
            Shorty.WUI.Dialog.reset(dialog)
          }).done(function(response){
            // add shorty to existing list
            Shorty.WUI.List.add([response.data],true);
            Shorty.WUI.List.dim(true)
            dfd.resolve(response);
          }).fail(function(response){
            Shorty.WUI.List.dim(true)
            dfd.reject(response);
          })
  }, // Shorty.Tracking.list
} // Shorty.Tracking
