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

// some GENERAL initializations
$(document).ready(function(){
  // make notification closeable
  $("#content").find('#notification').bind('click',Shorty.WUI.Notification.hide);
  // add date picker options
  $("#controls").find('#until').datepicker({
    dateFormat :'dd-mm-yy',
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    showOn: "button",
    buttonImage: $("#controls").find('#until').eq(0).attr('icon'),
    buttonImageOnly: true
  });
}); // document.ready

// our library, coding UI and ACTION methods
Shorty =
{
  // ===== Shorty.WUI =====
  WUI:
  {
    // ===== Shorty.WUI.Controls =====
    Controls:
    {
      // ===== Shorty.WUI.Controls.init =====
      init: function(){
        var dfd = new $.Deferred();
        $.when(
          Shorty.WUI.Controls.toggle(),
          Shorty.WUI.Sums.fill()
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Controls.init
      // ===== Shorty.WUI.Controls.toggle =====
      toggle: function(){
        var dfd = new $.Deferred();
        Shorty.WUI.Notification.hide();
        // show or hide dialog
        var controls = $('#controls');
        if ( ! controls.is(':visible')){
          $.when(
            $.when(
              controls.slideDown('slow')
            ).done(Shorty.WUI.Sums.fill)
          ).done(dfd.resolve);
        }else{
          $.when(
            controls.slideUp('fast')
          ).done(dfd.resolve);
        }
        return dfd.promise();
      }, // Shorty.WUI.Controls.toggle
    }, // Shorty.WUI.Controls
    // ===== Shorty.WUI.Desktop =====
    Desktop:
    {
      // ===== Shorty.WUI.Desktop.show =====
      show: function(duration){
        duration = duration || 'slow';
        var dfd = new $.Deferred();
        $.when($('#desktop').fadeIn(duration)).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Desktop.show
      // ===== Shorty.WUI.Desktop.hide =====
      hide: function(duration){
        duration = duration || 'slow';
        var dfd = new $.Deferred();
        $.when($('#desktop').fadeOut(duration)).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Desktop.hide
    }, // Shorty.WUI.Desktop
    // ===== Shorty.WUI.Dialog =====
    Dialog:
    {
      // ===== Shorty.WUI.Dialog.execute =====
      execute: function(dialog){
        var dfd = new $.Deferred();
        switch ( dialog.attr('id') ){
          case 'dialog-add':
            $.when(
              Shorty.WUI.Notification.hide(),
              Shorty.Action.Url.add()
            ).done(dfd.resolve);
            break;
          case 'dialog-edit':
            $.when(
              Shorty.WUI.Notification.hide(),
              Shorty.Action.Url.edit()
            ).done(dfd.resolve);
            break;
          case 'dialog-del':
            $.when(
              Shorty.WUI.Notification.hide(),
              Shorty.Action.Url.del()
            ).done(dfd.resolve);
            break;
          default:
            dfd.resolve();
        }; // switch
        return dfd.promise();
      }, // Shorty.WUI.Dialog.execute
      // ===== Shorty.WUI.Dialog.reset =====
      reset: function(dialog){
        var dfd = new $.Deferred();
        if (dialog){
          // reset dialog fields
          $.when(
            $.each(dialog.find('.shorty-input'),function(){if($(this).is('[data]'))$(this).val($(this).attr('data'));}),
            $.each(dialog.find('.shorty-value'),function(){if($(this).is('[data]'))$(this).text($(this).attr('data'));}),
            $.each(dialog.find('.shorty-icon'), function(){if($(this).is('[data]'))$(this).attr('src',$(this).attr('data'));})
          ).done(dfd.resolve);
        }
        else
          dfd.resolve();
        return dfd.promise();
      }, // Shorty.WUI.Dialog.reset
      // ===== Shorty.WUI.Dialog.sharpen =====
      sharpen: function(dialog,sharp){
        var dfd = new $.Deferred();
        if (dialog){
          if (sharp){

          }else{
          }
        }
        dfd.resolve();
        return dfd.promise();
      }, // Shorty.WUI.Dialog.sharpen
      // ===== Shorty.WUI.Dialog.show =====
      show: function(dialog){
        var duration = 'slow';
        var dfd = new $.Deferred();
        if (dialog.is(':visible'))
          return;
        $.when(Shorty.WUI.Desktop.hide()).done(function(){
          // wipe (reset) dialog
          Shorty.WUI.Dialog.reset(dialog);
          // show dialog
          $.when(dialog.slideDown(duration)).done(function(){
            // initialize dialog
            switch(dialog.attr('id')){
              case 'dialog-add':
                dialog.find('#confirm').bind('click', {dialog: dialog}, function(event){event.preventDefault();Shorty.WUI.Dialog.execute(event.data.dialog);} );
                dialog.find('#target').bind('focusout', {dialog: dialog}, function(event){Shorty.WUI.Meta.collect(event.data.dialog);} );
                dialog.find('#target').focus();
                break;
              default:
                dialog.find('#title').focus();
            } // switch
            dfd.resolve();
          });
        });
        return dfd.promise();
      }, // Shorty.WUI.Dialog.show
      // ===== Shorty.WUI.Dialog.hide =====
      hide: function(dialog){
        var duration = 'slow';
        var dfd = new $.Deferred();
        if (!dialog.is(':visible'))
          dfd.resolve();
        else{
          $.when(dialog.slideUp(duration)).done(
            function(){
              switch ( dialog.attr('id') ){
                case 'dialog-add':
                  dialog.find('#confirm').unbind('click');
                  dialog.find('#target').unbind('focusout');
                  break;
                default:
              } // switch
              $.when(Shorty.WUI.Desktop.show()).done(dfd.resolve);
            }
          );
        }
        return dfd.promise();
      }, // Shorty.WUI.Dialog.hide
      // ===== Shorty.WUI.Dialog.toggle =====
      toggle: function(dialog){
        var dfd = new $.Deferred();
        Shorty.WUI.Notification.hide();
        // show or hide dialog
        if ( ! dialog.is(':visible'))
          $.when(Shorty.WUI.Dialog.show(dialog)).done(dfd.resolve);
        else 
          $.when(Shorty.WUI.Dialog.hide(dialog)).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Dialog.toggle
    }, // Shorty.WUI.Dialog
    // Shorty.WUI.Hourglass
    Hourglass:
    {
      // ===== Shorty.WUI.Hourglass.toggle =====
      toggle: function(show){
        var dfd = new $.Deferred();
        var hourglass = $('#desktop').find('.shorty-hourglass');
        if (show){
          if (hourglass.is(':visible'))
            dfd.resolve();
          else
            $.when(
              hourglass.fadeIn('fast')
            ).done(dfd.resolve);
        }else{
          if (!hourglass.is(':visible'))
            dfd.resolve();
          else
            $.when(
              hourglass.fadeOut('slow')
            ).done(dfd.resolve);
        }
        return dfd.promise();
      }, // Shorty.WUI.Hourglass.toggle
    }, // Shorty.WUI.Hourglass
    // ===== Shorty.WUI.List =====
    List:
    {
      // ===== Shorty.WUI.List.add =====
      add: function(list,hidden){
        var dfd = new $.Deferred();
        // clone dummy row from list: dummy is the only row with an empty id
        var dummy = $('.shorty-list tr').filter(function(){return (''==$(this).attr('id'));});
        var row, set;
        $.when(
          // insert list elements (sets) one by one
          $.each(list,function(i,set){
            row = dummy.clone();
            // set row id to entry key
            row.attr('id',set.key);
            // add attributes to row, as data and value
            $.each(['key','title','source','target','clicks','created','accessed','until','notes','favicon'],
              function(i,aspect){
                // enhance row with real set values
                row.attr('data-'+this,set[aspect]);
                // fill data into corresponsing column
                var content;
                switch(aspect)
                {
                  case 'favicon':
                    content='<img width="16" src="'+set[aspect]+'">';
                    break;
                  default:
                    content=set[aspect];
                } // switch
                if (hidden)
                  // row is meant to be shown only later, so keep it hidden
                  row.find('td').filter('#'+aspect).html('<span style="display:none;">'+content+'</span>');
                else
                  // row is meant to be shown immediately, typically when initializing the list
                  row.find('td').filter('#'+aspect).html('<span style="display:inline;">'+content+'</span>');
              }
            );
            dummy.after(row);
          }) // each
        ).done (dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.List.add
      // ===== Shorty.WUI.List.build =====
      build: function()
      {
        var dfd = new $.Deferred();
        // prepare loading
        $.when(
          Shorty.WUI.Hourglass.toggle(true),
          Shorty.WUI.List.dim(false)
        ).done(function(){
          // retrieve new entries
          $.when(
            Shorty.WUI.List.get(function(list){
              Shorty.WUI.List.fill(list);
            })
          ).done(function(){
            $.when(
              Shorty.WUI.List.show(),
              Shorty.WUI.List.dim(true)
            ).done(function(){
              Shorty.WUI.Hourglass.toggle(false)
              dfd.resolve();
            });
          })
        })
        return dfd.promise();
      }, // Shorty.WUI.List.build
      // ===== Shorty.WUI.List.dim =====
      dim: function(show){
        var duration = 'slow';
        var dfd = new $.Deferred();
        var list = $('#desktop').find('#list');
        var body = list.find('tbody');
        if (show)
        {
          $.when(
            body.fadeIn(duration)
          ).done(function(){
            // in addition, fade in any columns that were added, but not yet shown
            body.find('tr').each(function(){
              // only those rows that carry an id (not the dummy)
              if (   ''!=$(this).attr('id')
                  && 'none'==$(this).find('td').find('span').css('display') )
                $(this).find('td').find('span').effect('pulsate');
            });
          }).done(dfd.resolve);
        }else{
          if (!body.is(':visible'))
            dfd.resolve();
          else
          {
            $.when(
              body.fadeOut(duration)
            ).done(dfd.resolve);
          }
        }
        return dfd.promise();
      }, // Shorty.WUI.List.dim
      // ===== Shorty.WUI.List.empty =====
      empty: function()
      {
        var dfd = new $.Deferred();
        $.when(
          $('#desktop').find('#list').find('tbody').find('tr').each(function(){
            if(''!=$(this).attr('id'))
              $(this).remove();
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.List.empty
      // ===== Shorty.WUI.List.fill =====
      fill: function(list){
        var dfd = new $.Deferred();
        // prevent clicks whilst loading the list
        $.when(
          $('.shorty-link').unbind('click', Shorty.Action.Url.click),
          $('.shorty-edit').unbind('click', Shorty.Action.Url.edit),
          $('.shorty-delete').unbind('click', Shorty.Action.Url.del),
          Shorty.WUI.Sums.fill(),
          Shorty.WUI.List.empty(),
          Shorty.WUI.List.add(list,false),
          // reenable clicks after loading the list
          $('.shorty-link').click(Shorty.Action.Url.click),
          $('.shorty-edit').click(Shorty.Action.Url.edit),
          $('.shorty-del').click(Shorty.Action.Url.del)
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.List.fill
      // ===== Shorty.WUI.List.get =====
      get: function(callback){
        var dfd = new $.Deferred();
        $.when(
          $.ajax({
            url:     'ajax/list.php',
            cache:   false,
            success: function(response){
              var dfd = new $.Deferred();
              if ( 'error'==response.status ){
                Shorty.WUI.Notification.show(response.note,'debug');
              }else{
                Shorty.WUI.Notification.show(response.note,'info');
                if (callback){
                  $.when(
                    callback(response.data)
                  ).done(dfd.resolve);
                }
              } // if else
              return dfd.promise();
            }
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.List.get
      // ===== Shorty.WUI.List.hide =====
      hide: function(duration){
        duration = 'slow';
        var dfd = new $.Deferred();
        var list = $('#desktop').find('#list');
        if ( ! list.is(':visible'))
          dfd.resolve();
        else
        {
          $.when(
            list.fadeOut(duration)
          ).done(dfd.resolve);
        }
        return dfd.promise();
      }, // Shorty.WUI.List.hide
      // ===== Shorty.WUI.List.placeholder =====
      placeholder: function(){
        if ($('#list').find('tbody').find('tr').is(':visible'))
          $('#placeholder').fadeOut('slow');
        else
          $('#placeholder').fadeIn('fast');
      }, // Shorty.WUI.List.placeholder
      // ===== Shorty.WUI.List.show =====
      show: function(duration){
        duration = 'slow';
        var dfd = new $.Deferred();
        var list = $('#desktop').find('#list');
        if (list.is(':visible'))
          dfd.resolve();
        else
        {
          // list currently not visible, show it
          $.when(
            list.find('tbody').show(),
            list.fadeIn(duration)
          ).done(function(){
            dfd.resolve();
            Shorty.WUI.List.placeholder();
          });
        }
        return dfd.promise();
      }, // Shorty.WUI.List.show
      // ===== Shorty.WUI.List.sort =====
      sort: function(){
        $.when(
          Shorty.WUI.Hourglass.toggle(true),
          Shorty.WUI.List.dim(false)
        ).done(function(){
          // retrieve new entries
          $.when(
            Shorty.WUI.List.empty(),
            Shorty.WUI.List.fill(list)
          ).done(function(){
            $.when(
              Shorty.WUI.List.show(),
              Shorty.WUI.List.dim(true)
            ).done(function(){
              Shorty.WUI.Hourglass.toggle(false)
              dfd.resolve();
            });
          })
        })
      }, // Shorty.WUI.List.sort
      // ===== Shorty.WUI.List.toggle =====
      toggle: function(duration){
        duration = 'slow';
        var dfd = new $.Deferred();
        if (list.is(':visible'))
          return Shorty.WUI.List.hide();
        else
          return Shorty.WUI.List.show();
      }, // Shorty.WUI.List.toggle
      // ===== Shorty.WUI.List.Toolbar =====
      Toolbar:
      {
        // ===== Shorty.WUI.List.Toolbar.toggle =====
        toggle: function(duration){
          duration = duration || 'slow';
          var button=$('#list').find('#tools');
          var toolbar=$('#list').find('#toolbar');
          if (button.attr('data-plus')==button.attr('src')){
            button.attr('src',button.attr('data-minus'));
            toolbar.find('div').each(function(){$(this).slideDown(duration);});
          }else{
            button.attr('src',button.attr('data-plus'));
            toolbar.find('div').each(function(){$(this).slideUp(duration);});
          }
        }, // Shorty.WUI.List.Toolbar.toggle
      }, // Shorty.WUI.List.Toolbar
    }, // Shorty.WUI.List
    // ===== Shorty.WUI.Notification =====
    Notification:
    {
      // ===== Shorty.WUI.Notification.hide =====
      hide: function(){
        var dfd = new $.Deferred();
        $.when(
          $('#notification').fadeOut('fast').text('')
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Notification.hide
      // ===== Shorty.WUI.Notification.show =====
      show: function(message,level){
        level = level || 'info';
        var dfd = new $.Deferred();
        var duration = 'slow';
        var notification = $('#notification');
        if (message && message.length){
          $.when(
            notification.fadeOut('fast')
          ).done(function(){
            switch(level){
              case 'debug':
                // detect debug mode by checking, of function 'debug()' exists
                if ( Shorty.debug ){
                  Shorty.debug('Debug: '+message);
                  $.when(
                    notification.attr('title', 'debug message'),
                    notification.text('Debug: '+message),
                    notification.fadeIn(duration)
                  ).done(dfd.resolve);
                }
                else
                  dfd.resolve();
                break;
              case 'error':
                if (Shorty.debug)
                  Shorty.debug('Error: '+message);
                $.when(
                  notification.attr('title', 'error message'),
                  notification.text('Error: ' + message),
                  notification.fadeIn(duration)
                ).done(dfd.resolve);
                break;
              default: // 'info'
                if ( message.length ){
                  if (Shorty.debug)
                    Shorty.debug('Info: '+message);
                  $.when(
                    notification.text(message),
                    notification.fadeIn(duration)
                  ).done(dfd.resolve);
                }else{
                  $.when(
                    notification.text('')
                  ).done(dfd.resolve);
                }
            } // switch
          })
        } // if message
        return dfd.promise();
      }, // Shorty.WUI.Notification.show
    }, // Shorty.WUI.Notification
    // ===== Shorty.WUI.Meta: =====
    Meta:
    {
      // ===== Shorty.WUI.Meta.collect =====
      collect: function(dialog){
        var dfd = new $.Deferred();
        var target = $('#dialog-add').find('#target').val().trim();
        // don't bother getting active on empty input
        if ( ! target.length ){
          dialog.find('#target').focus();
          dfd.resolve();
          return dfd.promise();
        }
        // fill in fallback protocol scheme 'http' if none is specified
        var regexp = /^[a-zA-Z0-9]+\:\//;
        if ( ! regexp.test(target) ){
          target = 'http://' + target;
          dialog.find('#target').val(target);
        }
        // query meta data from target
        $.when(
          Shorty.WUI.Meta.get(target,function(meta){
            dialog.find('#target').val(meta.final);
            dialog.find('#meta').fadeTo('fast',0,function(){
              Shorty.WUI.Meta.reset(dialog);
              dialog.find('#staticon').attr('src',meta.staticon);
              dialog.find('#schemicon').attr('src',meta.schemicon);
              dialog.find('#favicon').attr('src',meta.favicon);
              dialog.find('#mimicon').attr('src',meta.mimicon);
              dialog.find('#explanation').html(meta.title?meta.title:'[ '+meta.explanation+' ]');
              dialog.find('#meta').fadeTo('fast',1);
            });
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Meta.collect
      // ===== Shorty.WUI.Meta.get =====
      get: function(target,callback){
        var dfd = new $.Deferred();
        $.when(
          $.ajax({
            url:     'ajax/meta.php',
            cache:   false,
            data:    { target: encodeURIComponent(target) },
            error:   function() { return ''; },
            success: function(response){
              if (Shorty.Debug) Shorty.Debug.log(response.note);
              if ('success'==response.status){
                if (callback) callback(response.data);
              }else{
                if (Shorty.Debug) Shorty.Debug.log(Shorty.Debug.dump(response.data));
              }
            }
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Meta.get
      // ===== Shorty.WUI.Meta.reset =====
      reset: function(dialog){
        dialog.find('#staticon').attr('src',dialog.find('#staticon').attr('data'));
        dialog.find('#schemicon').attr('src',dialog.find('#schemicon').attr('data'));
        dialog.find('#favicon').attr('src',dialog.find('#favicon').attr('data'));
        dialog.find('#mimicon').attr('src',dialog.find('#mimicon').attr('data'));
        dialog.find('#explanation').html(dialog.find('#explanation').attr('data'));
        dialog.find('#meta').fadeTo('fast',1);
      }, // Shorty.WUI.Meta.reset
    }, // Shorty.WUI.Meta
    // ===== Shorty.WUI.Sums =====
    Sums:
    {
      // ===== Shorty.WUI.Sums.fill =====
      fill: function(){
        var dfd = new $.Deferred();
        $.when(
          // update (set) sum values in the control bar
          Shorty.WUI.Sums.get(function(data){
            $('#controls').find('#sum_shortys').text(data.sum_shortys);
            $('#controls').find('#sum_clicks').text(data.sum_clicks);
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Sums.fill
      // ===== Shorty.WUI.Sums.get =====
      get: function(callback){
        var dfd = new $.Deferred();
        $.when(
          $.ajax({
            url:     'ajax/count.php',
            cache:   false,
            data:    { },
            success: function(response){
              if ( 'error'==response.status ){
                Shorty.WUI.Notification.show(response.note,'debug');
              }else{
                Shorty.WUI.Notification.show(response.note,'info');
                if (callback) callback(response.data);
              } // if else
            }
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Sums.get
    }, // Shorty.WUI.Sums
  }, // Shorty.WUI

  //==========

  Action:
  {
    // ===== Shorty.Action.Preference =====
    Preference:
    {
      // ===== Shorty.Action.Preference.get =====
      get:function(data){
        $.get(OC.filePath('shorty','ajax','preferences.php'),data);
      }, // Shorty.Action.Preference.get
      // ===== Shorty.Action.Preference.set =====
      set:function(data){
        $.post(OC.filePath('shorty','ajax','preferences.php'),data);
      }, // Shorty.Action.Preference.set
    }, // Shorty.Action.Preference
    // ===== Shorty.Action.Setting =====
    Setting:
    {
      // ===== Shorty.Action.Setting.get =====
      get:function(data){
        var dfd = new $.Deferred();
        var result = $.when(
          $.get(OC.filePath('shorty','ajax','settings.php'),data,
            function(reply){
              reply.each(function(key,val){result[key]=val;});
          })
        ).done(function(){
          dfd.resolve();
        });
        return dfd.promise();
      }, // Shorty.Action.Setting.get
      // ===== Shorty.Action.Setting.set =====
      set:function(data){
        $.post(OC.filePath('shorty','ajax','settings.php'),data);
      }, // Shorty.Action.Setting.set
    }, // Shorty.Action.Setting
    // ===== Shorty.Action.Url =====
    Url:
    {
      // ===== Shorty.Action.Url.add =====
      add:function(){
        var dfd = new $.Deferred();
        var dialog = $('#dialog-add');
        var target  = dialog.find('#target').val().trim() || '';
        var title   = dialog.find('#title').val().trim()  || '';
        var notes   = dialog.find('#notes').val().trim()  || '';
        var until   = dialog.find('#until').val().trim()  || '';
        // take over meta data retrieved before
        var favicon = dialog.find('#meta').find('#favicon').attr('src');
        if (''==title)
          title = dialog.find('#meta').find('#explanation').html();
        $.when(
          Shorty.WUI.Notification.hide(),
          // close and neutralize dialog
          Shorty.WUI.Dialog.hide(dialog),
          Shorty.WUI.List.dim(false),
          Shorty.WUI.List.show(),
          $.ajax({
            url:     'ajax/add.php',
            cache:   false,
            data:    { target:  encodeURIComponent(target),
                       title:   encodeURIComponent(title),
                       notes:   encodeURIComponent(notes),
                       until:   encodeURIComponent(until),
                       favicon: encodeURIComponent(favicon) },
            error:   function(){
              if (!typeof Shorty.Debug==="undefined")
                Shorty.Debug.log(this.data);
              return false;
            },
            success: function(response){
              if ( 'success'==response.status ){
                // show notification
                Shorty.WUI.Notification.show(response,'info');
                Shorty.WUI.Dialog.reset(dialog);
                // add shorty to existing list
                Shorty.WUI.List.add([response.data],true);
                Shorty.WUI.List.dim(true)
              }else{
                Shorty.WUI.Notification.show(response.note,'error');
              }
            }
          })
        ).done(dfd.resolve).fail(dfd.reject);
        return dfd.promise;
      }, // ===== Shorty.Action.Url.add =====
      // ===== Shorty.Action.Url.edit =====
      edit: function(){
        var dfd = new $.Deferred();
        var dialog = $('#dialog-edit');
        var key    = dialog.find('#key').val();
        var source = dialog.find('#source').val();
        var target = dialog.find('#target').val();
        var notes  = dialog.find('#notes').val();
        var until  = dialog.find('#until').val();
        $.when(
//          Shorty.WUI.Notification.hide(),
          $.ajax({
            url:     'ajax/edit.php',
            cache:   false,
            data:    { key: key,
                       source: encodeURI(source),
                       target: encodeURI(target),
                       notes:  encodeURI(notes),
                       until:  encodeURI(until) },
            success: function(data){
              // close and neutralize dialog
              Shorty.WUI.Dialog.hide(dialog);
              // show notification
              Shorty.WUI.Notification.show(response,'info');
              var record = $('.shorty-single[data-key = "' + key + '"]');
              record.children('.shorty-target:first').text(target);

              var record_notes = record.children('.shorty-notes:first').children('a:first');
              record_notes.attr('href', target);
              record_notes.text(notes);
              record.children('.shorty-until').html(until);
            }
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // ===== Shorty.Action.Url.edit =====
      // ===== Shorty.Action.Url.del =====
      del: function(){
        var dfd = new $.Deferred();
        var dialog = $('#dialog-edit');
        var key    = dialog.find('#key').val();
        $.when(
//          Shorty.WUI.Notification.hide(),
          $.ajax({
            url:     'ajax/del.php',
            cache:   false,
            data:    { key: key },
            success: function(data){
              // close and neutralize dialog
              Shorty.WUI.Dialog.hide(dialog);
              // show notification
              Shorty.WUI.Notification.show(response,'info');
              // hide and remove deleted entry
              // ...
            }
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // ===== Shorty.Action.Url.del =====
      // ===== Shorty.Action.Url.show =====
      show: function(){
        var dfd = new $.Deferred();
        var dialog = $('#dialog-edit');
        var key    = dialog.find('#key').val();
        var record = $(this).parent().parent();
        $('#shorty-add-key').val(record.attr('data-key'));
        $('#shorty-add-source').val(record.children('.shorty-source:first').text());
        $('#shorty-add-target').val(record.children('.shorty-target:first').text());
        $('#shorty-add-notes').val(record.children('.shorty-notes:first').text());
        $('#shorty-add-until').val(record.children('.shorty-until:first').text());
        $.when(
//          Shorty.WUI.Notification.hide(),
          function(){
            if ($('.shorty-add').css('display') == 'none'){
              $('.shorty-add').slideToggle();
            }
          },
          $('html, body').animate({ scrollTop: $('.shorty-menu').offset().top }, 500)
        ).done(dfd.resolve);
        return dfd.promise();
      }, // ===== Shorty.Action.Url.show =====
    }, // ===== Shorty.Action.Url =====
  }, // Shorty.Action

} // Shorty
