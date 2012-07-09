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
 * @access public
 * @author Christian Reiner
 */

$(document).ready(function(){
  var dfd = new $.Deferred();
  $.when(
    // load layout of dialog to show the list of tracked clicks
    Shorty.Tracking.init()
  ).pipe(function(){
    // bind actions to basic buttons
    Shorty.Tracking.dialog.find('#close').bind('click',function(){
      Shorty.WUI.Dialog.hide(Shorty.Tracking.dialog);
    });
    Shorty.Tracking.dialog.find('#list #titlebar').bind('click',function(){
      Shorty.WUI.List.Toolbar.toggle(Shorty.Tracking.list,Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking);
    });
    Shorty.Tracking.dialog.find('#list #toolbar').find('#reload').bind('click',Shorty.Tracking.build);
    // title & target filter reaction
    Shorty.Tracking.list.find('thead tr#toolbar').find('th#time,th#address,th#host,th#user').find('#filter').bind('keyup',function(){
      Shorty.WUI.List.filter(
        Shorty.Tracking.list,
        $($(this).context.parentElement.parentElement).attr('id'),
        $(this).val(),
        Shorty.WUI.List.fill_callbackFilter_tracking
      );
    });
    // status filter reaction
    Shorty.Tracking.list.find('thead tr#toolbar th#result select').change(function(){
      Shorty.WUI.List.filter(
        Shorty.Tracking.list,
        $(this).parents('th').attr('id'),
        $(this).find(':selected').val(),
        Shorty.WUI.List.fill_callbackFilter_tracking
      );
    });
  }).done(dfd.resolve).fail(dfd.reject);
  return dfd.promise();
}); // document.ready

/**
 * @brief Method collection private to this plugin
 * @author Christian Reiner
 */
Shorty.Tracking=
{
  /**
   * @brief Persistent jQuery object holding the dialog implemented by this plugin
   * @author Christian Reiner
   */
  dialog:{},
  /**
   * @brief Persistent alphanumerical id referencing the Shorty this plugin currently deals with
   * @author Christian Reiner
   */
  id:{},
  /**
   * @brief Persistent jQuery object describing the list contained in this plugins dialog
   * @author Christian Reiner
   */
  list:{},
  /**
   * @method Shorty.Tracking.build
   * @brief Builds the content of the list of tracked clicks
   * @return deferred.promise
   * @author Christian Reiner
   */
  build: function(){
    if (Shorty.Debug) Shorty.Debug.log("building tracking list");
    var dfd = new $.Deferred();
    var fieldset=Shorty.Tracking.dialog.find('fieldset');
    // prepare loading
    $.when(
      Shorty.WUI.List.dim(Shorty.Tracking.list,false),
      // force current height of dialog whilst refreshing the content to prevent flickering height
      Shorty.Tracking.list.parent().css('height',Shorty.Tracking.list.parent().css('height'))
    ).done(function(){
      // retrieve new entries
      Shorty.WUI.List.empty(Shorty.Tracking.list);
      $.when(
        Shorty.Tracking.get(Shorty.Tracking.id)
      ).pipe(function(response){
        Shorty.WUI.List.fill(Shorty.Tracking.list,
                             response.data,
                             Shorty.WUI.List.fill_callbackFilter_tracking,
                             Shorty.WUI.List.add_callbackAppend_tracking);
      }).done(function(){
        $.when(
          // remove forced height added above to prevent fickering height
          Shorty.Tracking.list.parent().css('height',''),
          Shorty.WUI.List.dim(Shorty.Tracking.list,true)
        ).done(dfd.resolve).fail(dfd.reject)
      }).fail(function(){
        dfd.reject();
      })
    })
    return dfd.promise();
  }, // Shorty.Tracking.build
  /**
   * @method Shorty.Tracking.control
   * @brief Central control method, called by the app to hand over control
   * @param entry jQuery object holding the clicked entry, in this case a row in the list of Shortys
   * @return deferred.promise
   * @description This is the method specified as control in slot "registerActions"
   * @author Christian Reiner
   */
  control:function(entry){
    if (Shorty.Debug) Shorty.Debug.log("tracking list controller");
    var dfd=new $.Deferred();
    var dialog=Shorty.Tracking.dialog;
    // this is the shortys id
    Shorty.Tracking.id=entry.attr('id');
    // update lists reference bar content to improve intuitivity
    var title=Shorty.Tracking.dialog.find('#shorty-reference #title');
    title.html(title.attr('data-slogan')+': '+entry.attr('data-title'));
    var clicks=Shorty.Tracking.dialog.find('#shorty-reference #clicks');
    clicks.html(clicks.attr('data-slogan')+': '+entry.attr('data-clicks'));
    // prepare to (re-)fill the list
    $.when(
      Shorty.WUI.List.empty(dialog)
    ).done(function(){
      Shorty.WUI.Dialog.show(dialog)
      dfd.resolve();
    }).fail(function(){
      dfd.reject();
    })
    // load first content into the list
    Shorty.Tracking.build();
    return dfd.promise();
  }, // Shorty.Tracking.control
  /**
   * @method Shorty.Tracking.get
   * @brief Fetches a list of all registered clicks matching a specified Shorty
   * @param shorty string Id of the Shorty the click list is requested for
   * @param offset Numeric id of the last click that is already present in the list (ids being in chronological order!)
   * @return deferred.promise
   * @author Christian Reiner
   */
  get:function(shorty,offset){
    if (Shorty.Debug) Shorty.Debug.log("loading clicks into tracking list");
    // no offset specified ? then start at the beginning
    offset = offset || 0;
    var dfd=new $.Deferred();
    // retrieve list template
//     var data={shorty:shorty,offset:offset};
    var data={shorty:shorty}
    $.ajax({
      type:     'GET',
      url:      OC.filePath('shorty-tracking','ajax','get.php'),
      cache:    false,
      data:     data,
      dataType: 'json'
    }).pipe(
      function(response){return Shorty.Ajax.eval(response)},
      function(response){return Shorty.Ajax.fail(response)}
    ).done(function(response){
      dfd.resolve(response);
    }).fail(function(response){
      dfd.reject(response);
    })
    return dfd.promise();
  }, // Shorty.Tracking.get
  /**
   * @class Shorty.Tracking
   * @brief Initializes the dialog this aplugin adds to the Shorty app
   * @description The html content of the dialog is fetched via ajax
   * @return deferred.promise
   * @author Christian Reiner
   */
  init:function(){
    if (Shorty.Debug) Shorty.Debug.log("initializing tracking list");
    var dfd=new $.Deferred();
    // does the dialog holding the list exist already ?
    if (!$.isEmptyObject(Shorty.Tracking.dialog)){
      // remove all (real) entries so that the table can be filled again
      $('#shorty-tracking-list-dialog #list tr:not(#)').remove();
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
        Shorty.Tracking.list  =Shorty.Tracking.dialog.find('#list').eq(0);
        dfd.resolve(response);
      }).fail(function(response){
        dfd.reject(response);
      })
    } // else
    return dfd.promise();
  }
} // Shorty.Tracking

/**
 * @method Shorty.WUI.List.add_callbackAppend_tracking
 * @brief Callback function replacing the default used in Shorty.WUI.List.add()
 * @param row jQuery object Holding a raw clone of the 'dummy' entry in the list, meant to be populated by real values
 * @param set object This is the set of attributes describing a single registered click
 * @param hidden bool Indicats if new entries in lists should be held back for later highlighting (flashing) optically or not
 * @description This replacement uses the plugin specific column names
 * @author Christian Reiner
 */
Shorty.WUI.List.add_callbackAppend_tracking=function(row,set,hidden){
  // set row id to entry id
  row.attr('id',set.id);
  // handle all aspects, one by one
  $.each(['status','time','address','host','user','result'],
         function(j,aspect){
    if (hidden)
      row.addClass('shorty-fresh'); // might lead to a pulsate effect later
    // we wrap the cells content into a span tag
    var span=$('<span>');
    // enhance row with real set values
    if ('undefined'==set[aspect])
         row.attr('data-'+this,'');
    else row.attr('data-'+this,set[aspect]);
    // fill data into corresponsing column
    var title, content, classes=[];
    switch(aspect){
      case 'status':
        var icon;
        switch (set['result']){
          case 'blocked': icon='bad';     break;
          case 'denied':  icon='neutral'; break;
          case 'granted': icon='good';    break;
          default:        icon='blank';
        } // switch
        span.html('<img class="shorty-icon" width="16" src="'+OC.filePath('shorty','img/status',icon+'.png')+'">');
        break;
      case 'time':
        if (null==set[aspect])
             span.text('-?-');
        else span.text(formatDate(1000*set[aspect]));
        break;
      default:
        span.text(set[aspect]);
        span.addClass('ellipsis');
    } // switch
    row.find('td#'+aspect).empty().append(span);
  }) // each aspect
}, // Shorty.WUI.List.add_callbackAppend_tracking

/**
 * @method Shorty.WUI.List.fill_callbackFilter_tracking
 * @brief Column filter rules specific to this plugins list
 * @author Christian Reiner
 */
Shorty.WUI.List.fill_callbackFilter_tracking=function(list){
  if (Shorty.Debug) Shorty.Debug.log("using 'tracking' method to filter filled list");
  // filter list
  Shorty.WUI.List.filter(list,'time',   list.find('thead tr#toolbar th#time    #filter').val()),
  Shorty.WUI.List.filter(list,'address',list.find('thead tr#toolbar th#address #filter').val()),
  Shorty.WUI.List.filter(list,'host',   list.find('thead tr#toolbar th#host    #filter').val()),
  Shorty.WUI.List.filter(list,'user',   list.find('thead tr#toolbar th#user    #filter').val()),
  Shorty.WUI.List.filter(list,'result', list.find('thead tr#toolbar th#result  select :selected').val())
} // Shorty.WUI.List.fill_callbackFilter_tracking

/**
 * @method Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking
 * @brief Callback used to check if any filters prevent closing a lists toolbar
 * @param toolbar jQueryObject The lists toolbar filters should be checked in
 * @return bool Indicates if an existing filter prevents the closing or not
 * @description Used as a replacement for the default used in Shorty.WUI.List.Toolbar.toggle()
 * This version is private to this plugin and uses the filter names specific to the list of tracked clicks
 * @author Christian Reiner
 */
Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking=function(toolbar){
  return (  (  (toolbar.find('th#time,#address,#host,#user').find('div input#filter:[value!=""]').length)
             &&(toolbar.find('th#time,#address,#host,#user').find('div input#filter:[value!=""]').effect('pulsate')) )
          ||(  (toolbar.find('th#result select :selected').val())
             &&(toolbar.find('#result').effect('pulsate')) ) );
} // Shorty.WUI.List.Toolbar.toggle_callbackCheckFilter_tracking
