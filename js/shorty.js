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
 * @file js/settings.js
 * @brief Client side activity library
 * @author Christian Reiner
 */

/**
 * @class Shorty
 * @brief Central activity library for the client side
 * @author Christian Reiner
 */
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
        if (Shorty.Debug) Shorty.Debug.log("init controls");
        var dfd = new $.Deferred();
        $.when(
          Shorty.WUI.Controls.toggle(),
          Shorty.WUI.Sums.fill()
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Controls.init
      // ===== Shorty.WUI.Controls.toggle =====
      toggle: function(){
        if (Shorty.Debug) Shorty.Debug.log("toggle controls");
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
        if (Shorty.Debug) Shorty.Debug.log("show desktop");
        duration = duration || 'slow';
        var dfd = new $.Deferred();
        $.when($('#desktop').fadeTo(duration,1.0)).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Desktop.show
      // ===== Shorty.WUI.Desktop.hide =====
      hide: function(duration){
        if (Shorty.Debug) Shorty.Debug.log("hide desktop");
        duration = duration || 'slow';
        var dfd = new $.Deferred();
        $.when($('#desktop').fadeTo(duration,0.3)).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Desktop.hide
    }, // Shorty.WUI.Desktop
    // ===== Shorty.WUI.Dialog =====
    Dialog:
    {
      // ===== Shorty.WUI.Dialog.execute =====
      execute: function(dialog){
        if (Shorty.Debug) Shorty.Debug.log("execute dialog "+dialog.attr('id'));
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
      // ===== Shorty.WUI.Dialog.hide =====
      hide: function(dialog){
        if (Shorty.Debug) Shorty.Debug.log("hide dialog "+dialog.attr('id'));
        var duration = 'slow';
        var dfd = new $.Deferred();
        if (!dialog.is(':visible'))
          dfd.resolve();
        else{
          $.when(
            dialog.slideUp(duration)
          ).pipe(function(){
            switch ( dialog.attr('id') ){
              case 'dialog-add':
                dialog.find('#confirm').unbind('click');
                dialog.find('#target').unbind('focusout');
                break;
              default:
            } // switch
          }).pipe(function(){
            if (dialog.hasClass('shorty-standalone'))
              Shorty.WUI.Desktop.show();
          }).done(dfd.resolve);
        }
        return dfd.promise();
      }, // Shorty.WUI.Dialog.hide
      // ===== Shorty.WUI.Dialog.reset =====
      reset: function(dialog){
        if (Shorty.Debug) Shorty.Debug.log("reset dialog "+dialog.attr('id'));
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
      // ===== Shorty.WUI.Dialog.show =====
      show: function(dialog){
        if (Shorty.Debug) Shorty.Debug.log("show dialog "+dialog.attr('id'));
        var duration = 'slow';
        var dfd = new $.Deferred();
        if (dialog.is(':visible'))
          // dialog already open, nothing to do...
          dfd.resolve();
        else{
          $('#controls form.shorty-standalone').each(function(){
            Shorty.WUI.Dialog.hide($(this));
          });
          // hide 'old' notifications
          Shorty.WUI.Notification.hide(),
          // some preparations
          $.when(
            function(){
              var dfd = new $.Deferred();
              if (dialog.hasClass('shorty-standalone'))
                $.when(Shorty.WUI.Desktop.hide()).done(dfd.resolve);
              else dfd.resolve();
              return dfd.promise();
            }()
          ).pipe(function(){
            // wipe (reset) dialog
            Shorty.WUI.Dialog.reset(dialog);
            // show dialog
            dialog.slideDown(duration);
          }).pipe(function(){
            // initialize dialog
            dialog.find('#confirm').bind('click',   {dialog: dialog}, function(event){event.preventDefault();Shorty.WUI.Dialog.execute(event.data.dialog);} );
            dialog.find('#target').bind('focusout', {dialog: dialog}, function(event){Shorty.WUI.Meta.collect(event.data.dialog);} );
            switch(dialog.attr('id')){
              case 'dialog-add':
                dialog.find('#target').focus();
                break;
              case 'dialog-add':
                dialog.find('#title').focus();
                break;
              default:
                dialog.find('#title').focus();
            } // switch
          }).done(dfd.resolve);
        }
        return dfd.promise();
      }, // Shorty.WUI.Dialog.show
      // ===== Shorty.WUI.Dialog.toggle =====
      toggle: function(dialog){
        if (Shorty.Debug) Shorty.Debug.log("toggle dialog "+dialog.attr('id'));
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
    // ===== Shorty.WUI.Entry =====
    Entry:
    {
      // ===== Shorty.WUI.Entry.click =====
      click: function(event,element){
        var dfd = new $.Deferred();
        var entry=element.parents('tr').eq(0);
        if (Shorty.Debug) Shorty.Debug.log(event.type+" on action "+element.attr('id')+" for entry "+entry.attr('id'));
        $.when(
          Shorty.WUI.List.highlight(entry)
        ).pipe(function(){
          if ('click'==event.type){
            switch(element.attr('id')){
              default:
              case 'show':   Shorty.WUI.Entry.show(entry);   break;
              case 'share':  Shorty.WUI.Entry.share(entry);  break;
              case 'edit':   Shorty.WUI.Entry.edit(entry);   break;
              case 'delete': Shorty.WUI.Entry.delete(entry); break;
              case 'open':   Shorty.Action.Url.forward(entry);  break;
            } // switch
          } // if click
        }).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Entry.click
      // ===== Shorty.WUI.Entry.delete =====
      delete: function(entry){
        if (Shorty.Debug) Shorty.Debug.log("delete entry "+entry.attr('id'));
        if (entry.hasClass('deleted')){
          // change status to deleted
          Shorty.Action.Url.status(entry.attr('data-key'),'blocked');
          // mark row as undeleted
          entry.removeClass('deleted');
        }else{
          // change status to deleted
          Shorty.Action.Url.status(entry.attr('data-key'),'deleted');
          // mark row as deleted
          entry.addClass('deleted');
        }
      }, // Shorty.WUI.Entry.delete
      // ===== Shorty.WUI.Entry.edit =====
      edit: function(entry){
        if (Shorty.Debug) Shorty.Debug.log("modify entry "+entry.attr('id'));
        var dfd = new $.Deferred();
        // use the existing edit dialog for this
        var dialog=$('#controls #dialog-edit');
        // load entry into dialog
        dialog.find('#key').attr('data-key',entry.attr('data-key')).val(entry.attr('data-key'));
        dialog.find('#source').attr('data-source',entry.attr('data-source')).val(entry.attr('data-source'));
        dialog.find('#target').attr('data-target',entry.attr('data-target')).val(entry.attr('data-target'));
        dialog.find('#title').attr('data-title',entry.attr('data-title')).val(entry.attr('data-title'));
        dialog.find('#until').attr('data-until',entry.attr('data-until')||'').val(entry.attr('data-until')||'');
        dialog.find('#notes').attr('data-notes',entry.attr('data-notes')).val(entry.attr('data-notes'));
        // open edit dialog
        $.when(
          Shorty.WUI.Dialog.show(dialog)
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Entry.edit
      // ===== Shorty.WUI.Entry.share =====
      share: function(entry){
        if (Shorty.Debug) Shorty.Debug.log("share entry "+entry.attr('id'));
        var dfd = new $.Deferred();
        // use the existing 'share' dialog for this
        var dialog=$('#dialog-share');
        // fill dialog
        dialog.find('#source').attr('href',entry.attr('data-source')).text(entry.attr('data-source')),
        dialog.find('#target').attr('href',entry.attr('data-target')).text(entry.attr('data-target')),
        dialog.find('#status').attr('value',entry.attr('data-status')).attr('data',entry.attr('data-status')),
        // move 'share' dialog towards entry
        dialog.appendTo(entry.find('td#actions')),
        // open dialog
        $.when(
          Shorty.WUI.Dialog.show(dialog)
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Entry.share
      // ===== Shorty.WUI.Entry.show =====
      show: function(entry){
        if (Shorty.Debug) Shorty.Debug.log("show entry "+entry.attr('id'));
      } // Shorty.WUI.Entry.show
    }, // Shorty.WUI.Entry
    // ===== Shorty.WUI.Hourglass =====
    // Shorty.WUI.Hourglass
    Hourglass:
    {
      // ===== Shorty.WUI.Hourglass.toggle =====
      toggle: function(show){
        if (Shorty.Debug) Shorty.Debug.log("toggle hourglass to "+show?"true":"false");
        var dfd = new $.Deferred();
        var hourglass = $('#desktop .shorty-hourglass');
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
        if (Shorty.Debug) Shorty.Debug.log("add entry to list holding "+list.length+" entries");
        var dfd = new $.Deferred();
        // insert list elements (sets) one by one
        var row,set;
        $.each(list,function(i,set){
          // clone dummy row from list header: dummy is the last row
          row = $('#desktop #list thead tr:last-child').eq(0).clone();
          // set row id to entry key
          row.attr('id',set.key);
          // add attributes to row, as data and value
          $.each(['key','status','title','source','target','clicks','created','accessed','until','notes','favicon'],
                 function(j,aspect){
            if (set[aspect]){
              // enhance row with real set values
              row.attr('data-'+this,set[aspect]);
              if (hidden) row.addClass('shorty-pulsate');
              // fill data into corresponsing column
              var content, classes=[];
              switch(aspect)
              {
                case 'favicon':
                  content='<img class="shorty-icon" width="16" src="'+set[aspect]+'">';
                  break;
                case 'until':
                  if (null==set[aspect])
                    content='-/-';
                  else{
                    content=set[aspect];
                    if (Shorty.Date.expired(set[aspect]))
                      row.addClass('shorty-expired');
                  }
                  break;
                case 'title':
                case 'target':
                  classes.push('ellipsis');
                  content=set[aspect];
                  break;
                case 'status':
                  if ('deleted'==set[aspect])
                    row.addClass('deleted');
                  content=set[aspect];
                  break;
                default:
                  content=set[aspect];
              } // switch
              // insert new content into row cell
              row.find('td').filter('#'+aspect).html('<span class="'+classes.join(' ')+'">'+content+'</span>');
            } // if aspect
          }); // each aspect
          // insert new row in table
          $('#desktop #list tbody').prepend(row);
        }) // each
        return dfd.promise();
      }, // Shorty.WUI.List.add
      // ===== Shorty.WUI.List.build =====
      build: function()
      {
        if (Shorty.Debug) Shorty.Debug.log("build list");
        var dfd = new $.Deferred();
        // prepare loading
        $.when(
          Shorty.WUI.Hourglass.toggle(true),
          Shorty.WUI.List.dim(false)
        ).done(function(){
          // retrieve new entries
          $.when(
            Shorty.WUI.List.get()
          ).pipe(function(response){Shorty.WUI.List.fill(response.data);}
          ).done(function(){
            $.when(
              Shorty.WUI.List.show(),
              Shorty.WUI.List.dim(true)
            ).always(function(){
              Shorty.WUI.Hourglass.toggle(false)
              dfd.resolve();
            });
          }).fail(function(){
            dfd.reject();
          })
        })
        return dfd.promise();
      }, // Shorty.WUI.List.build
      // ===== Shorty.WUI.List.dim =====
      dim: function(show){
        if (Shorty.Debug) Shorty.Debug.log("dim list to "+(show?"true":"false"));
        var duration='slow';
        var dfd =new $.Deferred();
        var list=$('#desktop #list');
        var body=list.find('tbody');
        if (show)
        {
          var rows=body.find('tr.shorty-pulsate');
          Shorty.WUI.List.highlight(rows.eq(0));
          rows.each(function(){
            $(this).removeClass('shorty-pulsate');
            $(this).find('td').effect('pulsate');
          });
          $.when(
            body.fadeIn(duration)
          ).done(dfd.resolve);
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
      empty: function(){
        if (Shorty.Debug) Shorty.Debug.log("empty list");
        var dfd = new $.Deferred();
        $.when(
          $('#desktop').find('#list tbody tr').each(function(){
            if(''!=$(this).attr('id'))
              $(this).remove();
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.List.empty
      // ===== Shorty.WUI.List.fill =====
      fill: function(list){
        if (Shorty.Debug) Shorty.Debug.log("fill list");
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
      get: function(){
        if (Shorty.Debug) Shorty.Debug.log("get list");
        var dfd = new $.Deferred();
        $.when(
          $.ajax({
            url:     'ajax/list.php',
            cache:   false
          }).pipe(
            function(response){return Shorty.Ajax.eval(response)},
            function(response){return Shorty.Ajax.fail(response)}
          )
        ).done(function(response){
          dfd.resolve(response);
        }).fail(function(response){
          dfd.reject(response);
        });
        return dfd.promise();
      }, // Shorty.WUI.List.get
      // ===== Shorty.WUI.List.hide =====
      hide: function(duration){
        if (Shorty.Debug) Shorty.Debug.log("hide list");
        duration = 'slow';
        var dfd = new $.Deferred();
        var list = $('#desktop #list');
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
      // ===== Shorty.WUI.List.highlight =====
      highlight: function(entry){
        if (Shorty.Debug) Shorty.Debug.log("highlighting list entry "+entry.attr('id'));
        var dfd = new $.Deferred();
        // close any open embedded dialog
        $.when(
          Shorty.WUI.Dialog.hide($('#dialog-share').eq(0))
        ).pipe(function(){
          // neutralize all rows that might have been highlighted
          $('#desktop #list tr').removeClass('clicked');
          entry.addClass('clicked');
        }).always(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.List.highlight
      // ===== Shorty.WUI.List.modify =====
      modify: function(list,hidden){
        if (Shorty.Debug) Shorty.Debug.log("modify entry in list holding "+list.length+" entries");
        var dfd = new $.Deferred();
        // modify list elements (sets) one by one
        var row,set;
        $.each(list,function(i,set){
          // select row from list by key
          row=$('#desktop #list tbody tr#'+set.key);
          // modify attributes in row, as data and value
          $.each(['status','title','until','notes'],
                 function(j,aspect){
            if (set[aspect]){
              // enhance row with actual set values
              row.attr('data-'+this,set[aspect]);
              if (hidden) row.addClass('shorty-pulsate');
              // fill data into corresponsing column
              var content, classes=[];
              switch(aspect)
              {
                case 'until':
                  if (null==set[aspect])
                    content='-/-';
                  else{
                    content=set[aspect];
                    if (Shorty.Date.expired(set[aspect]))
                      row.addClass('shorty-expired');
                  }
                  break;
                case 'title':
                  classes.push('ellipsis');
                  content=set[aspect];
                  break;
                case 'status':
                  if ('deleted'==set[aspect])
                    row.addClass('deleted');
                  content=set[aspect];
                  break;
                default:
                  content=set[aspect];
              } // switch
              // show modified column immediately or keep it for a later pulsation effect ?
              row.find('td').filter('#'+aspect).html('<span class="'+classes.join(' ')+'">'+content+'</span>');
            } // if aspect
          }) // each aspect
        }) // each entry
        return dfd.resolve().promise();
      }, // Shorty.WUI.List.modify
      // ===== Shorty.WUI.List.show =====
      show: function(duration){
        if (Shorty.Debug) Shorty.Debug.log("show list");
        duration = 'slow';
        var dfd = new $.Deferred();
        var list = $('#desktop #list');
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
            Shorty.WUI.List.vacuum();
          });
        }
        return dfd.promise();
      }, // Shorty.WUI.List.show
      // ===== Shorty.WUI.List.sort =====
      sort: function(){
        if (Shorty.Debug) Shorty.Debug.log("sort list");
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
        if (Shorty.Debug) Shorty.Debug.log("toggle list");
        duration = 'slow';
        var dfd = new $.Deferred();
        if (list.is(':visible'))
          return Shorty.WUI.List.hide();
        else
          return Shorty.WUI.List.show();
      }, // Shorty.WUI.List.toggle
      // ===== Shorty.WUI.List.vacuum =====
      vacuum: function(){
        if (Shorty.Debug) Shorty.Debug.log("vacuum list");
        // list if empty if one 1 row is contained (the dummy)
        if ($('#list tbody').find('tr').length)
          $('#vacuum').fadeOut('fast');
        else
          $('#vacuum').fadeIn('slow');
      }, // Shorty.WUI.List.vacuum
      // ===== Shorty.WUI.List.Toolbar =====
      Toolbar:
      {
        // ===== Shorty.WUI.List.Toolbar.toggle =====
        toggle: function(duration){
          if (Shorty.Debug) Shorty.Debug.log("toggle list toolbar");
          duration = duration || 'slow';
          var button=$('#list #tools');
          var toolbar=$('#list #toolbar');
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
        if (Shorty.Debug) Shorty.Debug.log("hide notification");
        var dfd = new $.Deferred();
        $.when(
          $('#notification').slideUp('fast')
        ).pipe(function(){
          $('#notification').text('');
        }).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Notification.hide
      // ===== Shorty.WUI.Notification.show =====
      show: function(message,level){
        if (Shorty.Debug) Shorty.Debug.log("show notification with level "+level);
        level = level || 'info';
        var dfd = new $.Deferred();
        var duration = 'slow';
        var notification = $('#notification');
        if (message && message.length){
          $.when(
            notification.slideUp('fast')
          ).done(function(){
            switch(level){
              case 'debug':
                // detect debug mode by checking, of function 'debug()' exists
                if ( Shorty.Debug ){
                  Shorty.Debug.log('Debug: '+message);
                  $.when(
                    notification.attr('title', 'debug message'),
                    notification.text('Debug: '+message),
                    notification.slideDown(duration)
                  ).done(dfd.resolve);
                }
                else
                  dfd.resolve();
                break;
              case 'error':
                if (Shorty.Debug)
                  Shorty.Debug.log('Error: '+message);
                $.when(
                  notification.attr('title', 'error message'),
                  notification.text('Error: ' + message),
                  notification.slideDown(duration)
                ).done(dfd.resolve);
                break;
              default: // 'info'
                if ( message.length ){
                  if (Shorty.Debug)
                    Shorty.Debug.log('Info: '+message);
                  $.when(
                    notification.text(message),
                    notification.slideDown(duration)
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
        if (Shorty.Debug) Shorty.Debug.log("collect meta data");
        var dfd = new $.Deferred();
        var target = $('#dialog-add #target').val().trim();
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
          Shorty.WUI.Meta.get(target)
        ).done(function(response){
          var meta=response.data;
          if (meta.final)
            dialog.find('#target').val(meta.final);
          dialog.find('#title').attr('placeholder',meta.title);
          dialog.find('#meta').fadeTo('fast',0,function(){
            Shorty.WUI.Meta.reset(dialog);
            dialog.find('#staticon').attr('src',meta.staticon);
            dialog.find('#schemicon').attr('src',meta.schemicon);
            dialog.find('#favicon').attr('src',meta.favicon);
            dialog.find('#mimicon').attr('src',meta.mimicon);
            dialog.find('#explanation').html(meta.title?meta.title:'[ '+meta.explanation+' ]');
            dialog.find('#meta').fadeTo('fast',1);
          });
          dfd.resolve(response);
        }).fail(function(reponse){
          dfd.reject(response);
        });
        return dfd.promise();
      }, // Shorty.WUI.Meta.collect
      // ===== Shorty.WUI.Meta.get =====
      get: function(target){
        if (Shorty.Debug) Shorty.Debug.log("get meta data for target "+target);
        var dfd = new $.Deferred();
        $.ajax({
          url:     'ajax/meta.php',
          cache:   false,
          data:    { target: encodeURIComponent(target) }
        }).pipe(
          function(response){return Shorty.Ajax.eval(response);},
          function(response){return Shorty.Ajax.fail(response);}
        ).done(function(response){
          dfd.resolve(response);
        }).fail(function(response){
          dfd.reject(response);
        });
        return dfd.promise();
      }, // Shorty.WUI.Meta.get
      // ===== Shorty.WUI.Meta.reset =====
      reset: function(dialog){
        if (Shorty.Debug) Shorty.Debug.log("reset meta data");
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
        if (Shorty.Debug) Shorty.Debug.log("fill sums");
        var dfd = new $.Deferred();
        $.when(
          // update (set) sum values in the control bar
          Shorty.WUI.Sums.get(function(data){
            $('#controls #sum_shortys').text(data.sum_shortys);
            $('#controls #sum_clicks').text(data.sum_clicks);
          })
        ).done(dfd.resolve);
        return dfd.promise();
      }, // Shorty.WUI.Sums.fill
      // ===== Shorty.WUI.Sums.get =====
      get: function(callback){
        if (Shorty.Debug) Shorty.Debug.log("get sums");
        var dfd = new $.Deferred();
        $.when(
          $.ajax({
            url:     'ajax/count.php',
            cache:   false,
            data:    { }
          }).pipe(
            function(response){return Shorty.Ajax.eval(response)},
            function(response){return Shorty.Ajax.fail(response)}
          )
        ).done(function(response){
          if (callback) callback(response.data);
          dfd.resolve(response);
        }).fail(function(response){
          dfd.reject(response);
        });
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
        if (Shorty.Debug) Shorty.Debug.log("get preference");
        $.get(OC.filePath('shorty','ajax','preferences.php'),data);
      }, // Shorty.Action.Preference.get
      // ===== Shorty.Action.Preference.set =====
      set:function(data){
        if (Shorty.Debug) Shorty.Debug.log("set preference");
        $.post(OC.filePath('shorty','ajax','preferences.php'),data);
      }, // Shorty.Action.Preference.set
    }, // Shorty.Action.Preference
    // ===== Shorty.Action.Setting =====
    Setting:
    {
      // ===== Shorty.Action.Setting.get =====
      get:function(data){
        if (Shorty.Debug) Shorty.Debug.log("get setting");
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
        if (Shorty.Debug) Shorty.Debug.log("set setting:");
        if (Shorty.Debug) Shorty.Debug.log(data);
        $.post(OC.filePath('shorty','ajax','settings.php'),data);
      }, // Shorty.Action.Setting.set
    }, // Shorty.Action.Setting
    // ===== Shorty.Action.Url =====
    Url:
    {
      // ===== Shorty.Action.Url.add =====
      add:function(){
        if (Shorty.Debug) Shorty.Debug.log("action add url");
        var dfd=new $.Deferred();
        var dialog=$('#dialog-add');
        var status=dialog.find('#status').val()||'public';
        var target=dialog.find('#target').val()||'';
        var title =dialog.find('#title').val()||'';
        var notes =dialog.find('#notes').val()||'';
        var until =dialog.find('#until').val()||'';
        // store favicon from meta data, except it is the internal default blank
        var favicon = dialog.find('#meta #favicon').attr('src');
        favicon=(favicon==dialog.find('#meta #favicon').attr('data'))?'':favicon;
        // perform upload of new shorty
        $.when(
          Shorty.WUI.Notification.hide(),
          // close and neutralize dialog
          Shorty.WUI.Dialog.hide(dialog),
          Shorty.WUI.List.dim(false),
          Shorty.WUI.List.show()
        ).done(function(){
          var data={status:  encodeURIComponent(status),
                    target:  encodeURIComponent(target),
                    title:   encodeURIComponent(title),
                    notes:   encodeURIComponent(notes),
                    until:   encodeURIComponent(until),
                    favicon: encodeURIComponent(favicon)};
          if (Shorty.Debug) Shorty.Debug.log(data);
          $.ajax({
            url:   'ajax/add.php',
            cache: false,
            data:  data
          }).pipe(
            function(response){return Shorty.Ajax.eval(response)},
            function(response){return Shorty.Ajax.fail(response)}
          ).done(function(response){
            Shorty.WUI.Dialog.reset(dialog);
            // add shorty to existing list
            Shorty.WUI.List.add([response.data],true);
            Shorty.WUI.List.dim(true)
            dfd.resolve(response);
          }).fail(function(response){
            dfd.reject(response);
          });
        });
        return dfd.promise();
      }, // ===== Shorty.Action.Url.add =====
      // ===== Shorty.Action.Url.edit =====
      edit: function(){
        if (Shorty.Debug) Shorty.Debug.log("action modify url");
        var dfd=new $.Deferred();
        var dialog=$('#dialog-edit');
        var key   =dialog.find('#key').val();
        var status=dialog.find('#status').val()||'public';
        var title =dialog.find('#title').val()||'';
        var until =dialog.find('#until').val()||'';
        var notes =dialog.find('#notes').val()||'';
        // perform modification of existing shorty
        $.when(
          Shorty.WUI.Notification.hide(),
          // close and neutralize dialog
          Shorty.WUI.Dialog.hide(dialog),
          Shorty.WUI.List.dim(false),
          Shorty.WUI.List.show()
        ).done(function(){
          var data={key: key,
                    status: encodeURI(status),
                    title:  encodeURI(title),
                    notes:  encodeURI(notes),
                    until:  encodeURI(until) };
          if (Shorty.Debug) Shorty.Debug.log(data);
          $.ajax({
            url:   'ajax/edit.php',
            cache: false,
            data:  data,
          }).pipe(
            function(response){return Shorty.Ajax.eval(response)},
            function(response){return Shorty.Ajax.fail(response)}
          ).done(function(response){
            Shorty.WUI.Dialog.reset(dialog);
            // modify existing entry in list
            Shorty.WUI.List.modify([response.data],true);
            Shorty.WUI.List.dim(true)
            dfd.resolve(response);
          }).fail(function(response){
            dfd.reject(response);
          });
        });
        return dfd.promise();
      }, // ===== Shorty.Action.Url.edit =====
      // ===== Shorty.Action.Url.del =====
      del: function(){
        if (Shorty.Debug) Shorty.Debug.log("action delete url");
        var dfd = new $.Deferred();
        var dialog = $('#dialog-edit');
        var key    = dialog.find('#key').val();
        $.when(
//          Shorty.WUI.Notification.hide(),
          $.ajax({
            url:     'ajax/del.php',
            cache:   false,
            data:    { key: key }
          }).pipe(
            function(response){return Shorty.Ajax.eval(response)},
            function(response){return Shorty.Ajax.fail(response)}
          )
        ).done(function(response){
          // close and neutralize dialog
          Shorty.WUI.Dialog.hide(dialog);
          // hide and remove deleted entry
          // ...
          dfd.resolve(response.data);
        }).fail(function(response){
          dfd.reject(response.data);
        });
        return dfd.promise();
      }, // ===== Shorty.Action.Url.del =====
      // ===== Shorty.Action.Url.forward =====
      forward: function(entry){
        if (Shorty.Debug) Shorty.Debug.log("action forward to entry "+entry.attr('id'));
        var url=entry.attr('data-target');
        if (Shorty.Debug) Shorty.Debug.log("opening target url '"+url+"' in new window");
        window.open(url);
      }, // Shorty.Action.Url.forward
      // ===== Shorty.Action.Url.show =====
      show: function(){
        var dfd = new $.Deferred();
        var dialog = $('#dialog-show');
        var key    = dialog.find('#key').val();
        var record = $(this).parent().parent();
        $('#shorty-add-key').val(record.attr('data-key'));
        $('#shorty-add-key').val(record.attr('data-status'));
        $('#shorty-add-source').val(record.children('.shorty-source:first').text());
        $('#shorty-add-target').val(record.children('.shorty-target:first').text());
        $('#shorty-add-notes').val(record.children('.shorty-notes:first').text());
        $('#shorty-add-until').val(record.children('.shorty-until:first').text());
        $.when(
          function(){
            if ($('.shorty-add').css('display') == 'none'){
              $('.shorty-add').slideToggle();
            }
          },
          $('html, body').animate({ scrollTop: $('.shorty-menu').offset().top }, 500)
        ).done(dfd.resolve);
        return dfd.promise();
      }, // ===== Shorty.Action.Url.show =====
      // ===== Shorty.Action.Url.status =====
      status: function(key,status){
        if (Shorty.Debug) Shorty.Debug.log("changing status of key "+key+" to "+status);
        var dfd = new $.Deferred();
        $.ajax({
          url:     'ajax/status.php',
          cache:   false,
          data:    { key:    key,
                     status: status }
        }).pipe(
          function(response){return Shorty.Ajax.eval(response)},
          function(response){return Shorty.Ajax.fail(response)}
        ).done(dfd.rsolve).fail(dfd.reject);
        return dfd.promise();
      } // Shorty.Action.Url.status
    }, // ===== Shorty.Action.Url =====
  }, // Shorty.Action

  // ===========

  // ===== Shorty.Ajax =====
  Ajax:
  {
    // ===== Shorty.Ajax.eval =====
    eval:function(response){
      if (Shorty.Debug) Shorty.Debug.log("eval ajax response of status "+response.status);
      // Check to see if the response is truely successful.
      if (response.status){
        // this is a valid response
        if ('success'==response.status){
          Shorty.WUI.Notification.show(response.message,'debug');
          return new $.Deferred().resolve(response);
        } else {
          Shorty.WUI.Notification.show(response.message,'error');
          return new $.Deferred().reject(response);
        }
//       }else{
  // TEST (regex) if this is a DB error:
  // DB Error: "SQLSTATE[HY000]: General error: 1 near "WHERE": syntax error".....
//         // not a valid response, maybe a DB error ?
//         if ('DB error'==response)
      }
    }, // Shorty.Ajax.eval

    // ===== Shorty.Ajax.fail =====
    fail:function(response){
      if (Shorty.Debug) Shorty.Debug.log("handle ajax failure");
      return new $.Deferred().reject({
        status: 'error',
        data: null,
        message: [ "Unexpected error: " + response.status + " " + response.statusText ]
      });
    } // Shorty.Ajax.fail
  }, // Shorty.Ajax

  // ===========

  // ==== Shorty.Date =====
  Date:
  {
    // ===== Shorty.Date.expired =====
    expired:function(date){
      return (Date.parse(date)<=Date.parse(Date()));
    } // Shorty.Date.expired
  } // Shorty.Date

} // Shorty
