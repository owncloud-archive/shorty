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
    Shorty.WUI.List.Toolbar.toggle(Shorty.Tracking.list,Shorty.WUI.List.Toolbar.checkFilter_tracking);
  });
  Shorty.Tracking.dialog.find('#list #toolbar').find('#reload').bind('click',Shorty.Tracking.build);
    // title & target filter reaction
    Shorty.Tracking.list.find('thead tr#toolbar').find('th#time,th#address,th#host,th#user').find('#filter').bind('keyup',function(){
      Shorty.WUI.List.filter(
        Shorty.Tracking.list,
        $($(this).context.parentElement.parentElement).attr('id'),
        $(this).val()
      );
    });
    // status filter reaction
    Shorty.Tracking.list.find('thead tr#toolbar th#result select').change(function(){
      Shorty.WUI.List.filter(
        Shorty.Tracking.list,
        $(this).parents('th').attr('id'),
        $(this).find(':selected').val()
      );
    });
  }).done(dfd.resolve).fail(dfd.reject);
  return dfd.promise();
}); // document.ready

Shorty.Tracking=
{
  // ===== Shorty.Tracking.dialog =====
  dialog:{},
  // ===== Shorty.Tracking.id =====
  id:{},
  // ===== Shorty.Tracking.list =====
  list:{},
  // ===== Shorty.Tracking.build =====
  build: function(){
    if (Shorty.Debug) Shorty.Debug.log("building tracking list");
    var dfd = new $.Deferred();
    // prepare loading
    $.when(
      Shorty.WUI.List.dim(Shorty.Tracking.list,false)
    ).done(function(){
      // retrieve new entries
      Shorty.WUI.List.empty(Shorty.Tracking.list);
      $.when(
        Shorty.Tracking.get(Shorty.Tracking.id)
      ).done(function(response){
        $.when(
          Shorty.WUI.List.add(Shorty.Tracking.list,response.data,false,Shorty.WUI.List.append_tracking)
        ).done(function(){
          Shorty.WUI.List.dim(Shorty.Tracking.list,true);
          dfd.resolve();
        }).fail(function(){
          dfd.reject();
        })
      }).fail(function(){
        dfd.reject();
      })
    })
    return dfd.promise();
  }, // Shorty.Tracking.build
  // ===== Shorty.Tracking.control =====
  control:function(entry){
    if (Shorty.Debug) Shorty.Debug.log("tracking list controller");
    var dfd=new $.Deferred();
    var dialog=Shorty.Tracking.dialog;
    // this is the shortys id
    Shorty.Tracking.id=entry.attr('id');
    // update lists reference bar content to improve intuitivity
    var title=Shorty.Tracking.dialog.find('#list thead tr#referencebar #title');
    title.html(title.attr('data-slogan')+': '+entry.attr('data-title'));
    var clicks=Shorty.Tracking.dialog.find('#list thead tr#referencebar #clicks');
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
    Shorty.Tracking.build(Shorty.Tracking.id);
    return dfd.promise();
  }, // Shorty.Tracking.control
  // ===== Shorty.Tracking.get =====
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
  // ===== Shorty.Tracking.init =====
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

// ===== Shorty.WUI.List.append_tracking =====
Shorty.WUI.List.append_tracking=function(row,set,hidden){
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
          case 'blocked': icon='bad';
          case 'denied':  icon='neutral';
          case 'granted': icon='good';
          default:        icon='blank';
        } // switch
        span.html('<img class="shorty-icon" width="16" src="'+OC.filePath('shorty','img/status',icon+'.png')+'">');
        break;
      case 'time':
        if (null==set[aspect])
             span.text('-?-');
        else span.text(set[aspect]);
        break;
      default:
        span.text(set[aspect]);
        span.addClass('ellipsis');
    } // switch
    row.find('td#'+aspect).empty().append(span);
  }) // each aspect
}, // Shorty.WUI.List.append_tracking

// ===== Shorty.WUI.List.Toolbar.checkFilter_tracking =====
Shorty.WUI.List.Toolbar.checkFilter_tracking=function(toolbar){
  return (  (  (toolbar.find('th#time,#address,#host,#user').find('div input#filter:[value!=""]').length)
             &&(toolbar.find('th#time,#address,#host,#user').find('div input#filter:[value!=""]').effect('pulsate')) )
          ||(  (toolbar.find('th#result select :selected').val())
             &&(toolbar.find('#result').effect('pulsate')) ) );
} // Shorty.WUI.List.Toolbar.checkFilter_tracking
