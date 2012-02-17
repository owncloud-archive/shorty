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


Shorty =
{
  // ===== Shorty.WUI =====
  WUI:
  {
    // ===== Shorty.WUI.Controls =====
    Controls:
    {
      // ===== Shorty.WUI.Controls.init =====
      init: function()
      {
        var dfd = new $.Deferred();
        $.when(
          Shorty.WUI.Controls.toggle(),
          Shorty.WUI.Sums.fill()
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Controls.init
      // ===== Shorty.WUI.Controls.toggle =====
      toggle: function()
      {
        var dfd = new $.Deferred();
        Shorty.WUI.Notification.hide();
        // show or hide dialog
        var controls = $('#controls');
        if ( ! controls.is(':visible'))
        {
          $.when(
            $.when(
              controls.slideDown('slow')
            ).then(Shorty.WUI.Sums.fill)
          ).then(function(){dfd.resolve();});
        }
        else
        {
          $.when(
            controls.slideUp('fast')
          ).then(function(){dfd.resolve();});
        }
        return dfd.promise();
      }, // Shorty.WUI.Controls.toggle
    }, // Shorty.WUI.Controls
    // ===== Shorty.WUI.Dialog =====
    Dialog:
    {
      // ===== Shorty.WUI.Dialog.reset =====
      reset: function(dialog)
      {
        var dfd = new $.Deferred();
        // reset dialog fields
        $.when(
          $.each(dialog.find('.shorty-input'),function(){if($(this).is('[data]'))$(this).val($(this).attr('data'));}),
          $.each(dialog.find('.shorty-value'),function(){if($(this).is('[data]'))$(this).text($(this).attr('data'));}),
          $.each(dialog.find('.shorty-icon'), function(){if($(this).is('[data]'))$(this).attr('src',$(this).attr('data'));})
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Dialog.reset
      // ===== Shorty.WUI.Dialog.submit =====
      submit: function(dialog)
      {
        var dfd = new $.Deferred();
        switch ( dialog.attr('id') )
        {
          case 'dialog-add':
            $.when(
              Shorty.WUI.Notification.hide(),
              Shorty.Action.Url.add(Shorty.WUI.Dialog.toggle(dialog))
            ).then(function(){dfd.resolve();});
            break;
          case 'dialog-edit':
            $.when(
              Shorty.WUI.Notification.hide(),
              Shorty.Action.Url.edit(Shorty.WUI.Dialog.toggle(dialog))
            ).then(function(){dfd.resolve();});
            break;
          case 'dialog-del':
            $.when(
              Shorty.WUI.Notification.hide(),
              Shorty.Action.Url.del(Shorty.WUI.Dialog.toggle(dialog))
            ).then(function(){dfd.resolve();});
            break;
          default:
            dfd.resolve();
        }; // switch
        return dfd.promise();
      }, // Shorty.WUI.Dialog.submit
      // ===== Shorty.WUI.Dialog.toggle =====
      toggle: function(dialog)
      {
        var duration = 'slow';
        var easing   = 'swing';
        var dfd = new $.Deferred();
        Shorty.WUI.Notification.hide();
        // show or hide dialog
        if ( ! dialog.is(':visible'))
        {
          // start sequence by hiding the desktop first
          $('#desktop').fadeOut(duration,function()
            {
              Shorty.WUI.Dialog.reset(dialog);
              // show dialog
              dialog.slideDown(duration);
              // initialize dialog
              switch(dialog.attr('id'))
              {
                case 'dialog-add':
                  dialog.find('#confirm').bind('click', {dialog: dialog}, function(event){Shorty.WUI.Dialog.submit(event.data.dialog);} );
                  dialog.find('#target').bind('focusout', {dialog: dialog}, function(event){Shorty.WUI.Meta.collect(event.data.dialog);} );
                  dialog.find('#target').focus();
                  break;
                default:
                  dialog.find('#title').focus();
              } // switch
            }
          );
        }
        else
        {
          // hide dialog
          dialog.slideUp(duration, function()
            {
              switch ( dialog.attr('id') )
              {
                case 'dialog-add':
                  dialog.find('#confirm').unbind('click');
                  dialog.find('#target').unbind('focusout');
                  break;
                default:
              } // switch
              $('#desktop').fadeIn(duration);
            }
          );
        }
        return dfd.promise();
      }, // Shorty.WUI.Dialog.toggle
    }, // Shorty.WUI.Dialog
    // Shorty.WUI.Hourglass
    Hourglass:
    {
      toggle: function(show)
      {
        var dfd = new $.Deferred();
        if (show)
          $.when(
            $('#desktop').find('.shorty-hourglass').fadeIn('fast')
          ).then(function(){dfd.resolve();});
        else
          $.when(
            $('#desktop').find('.shorty-hourglass').fadeOut('slow')
          ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Hourglass.toggle
    }, // Shorty.WUI.Hourglass
    // ===== Shorty.WUI.List =====
    List:
    {
      // ===== Shorty.WUI.List.toggle =====
      toggle: function(filled)
      {
        var duration = 'slow';
        var dfd = new $.Deferred();
        if (filled)
        {
          $.when
          (
            $('#desktop').find('#list-empty').hide(),
            $('#desktop').find('#list-nonempty').fadeIn(duration)
          ).then(function(){dfd.resolve();});
        }
        else
        {
          $.when
          (
            $('#desktop').find('#list-empty').fadeIn(duration),
            $('#desktop').find('#list-nonempty').hide()
          ).then(function(){dfd.resolve();});
        }
        return dfd.promise();
      }, // // Shorty.WUI.List.toggle
      // ===== Shorty.WUI.List.build =====
      build: function()
      {
        var dfd = new $.Deferred();
        // prepare loading
        $.when
        (
          Shorty.WUI.Notification.hide(),
          Shorty.WUI.Hourglass.toggle(true),
          Shorty.WUI.List.get(function(list){
            Shorty.WUI.List.fill(list,function(){
              Shorty.WUI.Hourglass.toggle(false);
            });
          })
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // build
      // ===== Shorty.WUI.List.fill =====
      fill: function(list,callback)
      {
        var dfd = new $.Deferred();
        if ( ! list.length )
        {
          // list empty, show placeholder instead of empty table
          $.when(Shorty.WUI.List.toggle(false)).then(function(){dfd.resolve();});
        }
        else
        {
          // list non-empty, fill and show table instead of placeholder
          // prevent clicks whilst loading the list
          $.when(
            $('.shorty-link').unbind('click', Shorty.Action.Url.click),
            $('.shorty-edit').unbind('click', Shorty.Action.Url.edit),
            $('.shorty-delete').unbind('click', Shorty.Action.Url.del),
            Shorty.WUI.Sums.fill(),
            Shorty.WUI.List.add(list),
            // display the list
            Shorty.WUI.List.toggle(true),
            // reenable clicks after loading the list
            $('.shorty-link').click(Shorty.Action.Url.click),
            $('.shorty-edit').click(Shorty.Action.Url.edit),
            $('.shorty-del').click(Shorty.Action.Url.del)
          ).then(function(){dfd.resolve();});
        }
        if (callback) callback();
        return dfd.promise();
      }, // Shorty.WUI.List.fill
      // ===== Shorty.WUI.List.add =====
      add: function(list,smooth)
      {
        var dfd = new $.Deferred();
        smooth = smooth || true;
        // clone dummy row from list: dummy is the only row with an empty id
        var dummy = $('.shorty-list tr').filter(function(){return (''==$(this).attr('id'));});
        var row, set;
        // insert list elements (sets) one by one
        for ( var i in list )
        {
          row = dummy.clone();
          set = list[i];
          // set row id to entry key
          row.attr('id',set.key);
          // enhance row with real set values
          $.each(Array('key','source','target','clicks','created','accessed','until','notes'),
            function(){
              row.attr('data-'+this,set[this]);
              $.each(row.find('td'),function(){$(this).text(set[$(this).attr('id')]);});
            }
          );
          dummy.after(row);
          if (smooth)
              $.when(row.fadeIn('slow')).then(function(){dfd.resolve();});
          else $.when(row.attr('style','visible')).then(function(){dfd.resolve();});
        } // for
        return dfd.promise();
      }, // Shorty.WUI.List.add
      // ===== Shorty.WUI.List.get =====
      get: function(callback)
      {
        var dfd = new $.Deferred();
  // ToDo Fixme: correct ids of filter variables
        var target = $('#list-filter-target').val() || '';
        var title  = $('#list-filter-title').val()  || '';
        $.when(
          $.ajax
          (
            {
              url:     'ajax/list.php',
              cache:   false,
              data:    { target: encodeURI(target), title: encodeURI(title) },
              success: function(response)
              {
                var dfd = new $.Deferred();
                if ( 'error'==response.status )
                {
                  Shorty.WUI.Notification.show(response.note,'debug');
                }
                else
                {
                  Shorty.WUI.Notification.show(response.note,'info');
                  if (callback)
                    $.when(
                      callback(response.data)
                    ).then(function(){dfd.resolve();});
                } // if else
                return dfd.promise();
              }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.List.get
    }, // Shorty.WUI.List
    // ===== Shorty.WUI.Notification =====
    Notification:
    {
      // ===== Shorty.WUI.Notification.hide =====
      hide: function()
      {
        var dfd = new $.Deferred();
        $.when(
          $('#notification').fadeOut('fast').text('')
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Notification.hide
      // ===== Shorty.WUI.Notification.show =====
      show: function(message,level)
      {
        level = level || 'info';
        var dfd = new $.Deferred();
        var duration = 'slow';
        var notification = $('#notification');
        switch(level)
        {
          case 'debug':
            // detect debug mode by checking, of function 'debug()' exists
            if ( Shorty.debug )
            {
              Shorty.debug('Debug: '+message);
              $.when(
                notification.fadeOut('fast'),
                notification.attr('title', 'debug message'),
                notification.text('Debug: '+message),
                notification.fadeIn(duration)
              ).then(function(){dfd.resolve();});
            }
            break;
          case 'error':
            if (Shorty.debug)
              Shorty.debug('Error: '+message);
            $.when(
              notification.fadeOut('fast'),
              notification.attr('title', 'error message'),
              notification.text('Error: ' + message),
              notification.fadeIn(duration)
            ).then(function(){dfd.resolve();});
            break;
          default: // 'info'
            if ( message.length )
            {
              if (Shorty.debug)
                Shorty.debug('Info: '+message);
              $.when(
                notification.fadeOut('fast'),
                notification.text(message),
                notification.fadeIn(duration)
              ).then(function(){dfd.resolve();});
            }
            else
            {
              $.when(
                notification.fadeOut('fast'),
                notification.text('')
              ).then(function(){dfd.resolve();});
            }
        } // switch
        return dfd.promise();
      }, // Shorty.WUI.Notification.show
    }, // Shorty.WUI.Notification
    // ===== Shorty.WUI.Meta: =====
    Meta:
    {
      // ===== Shorty.WUI.Meta.collect =====
      collect: function(dialog)
      {
        var dfd = new $.Deferred();
        var target = $('#dialog-add').find('#target').val().trim();
        // don't bother getting active on empty input
        if ( ! target.length )
        {
          dialog.find('#target').focus();
          dfd.resolve();
          return dfd.promise();
        }
        // fill in fallback protocol scheme 'http' if none is specified
        var regexp = /^[a-zA-Z0-9]+\:\//;
        if ( ! regexp.test(target) )
        {
          target = 'http://' + target;
          dialog.find('#target').val(target);
        }
        // query meta data from target
        Shorty.WUI.Meta.get(target,function(meta)
          {
            dialog.find('#meta').fadeTo ( 'fast', 0, function()
              {
                dialog.find('#staticon').attr('src',meta.staticon);
                dialog.find('#schemicon').attr('src',meta.schemicon);
                dialog.find('#favicon').attr('src',meta.favicon);
                dialog.find('#mimicon').attr('src',meta.mimicon);
                dialog.find('#explanation').html(meta.title?meta.title:meta.explanation);
                dialog.find('#meta').fadeTo('fast',1);
              }
            );
          }
        );
        return dfd.promise();
      }, // Shorty.WUI.Meta.collect
      // Shorty.WUI.Meta.get
      get: function(target,callback)
      {
        var dfd = new $.Deferred();
        $.when(
          $.ajax
          (
            {
              url:     'ajax/meta.php',
              cache:   false,
              data:    { target: encodeURIComponent(target) },
              error:   function() { return ''; },
              success: function(response)
              {
                if (Shorty.Debug) Shorty.Debug.log(response.note);
                if ('success'==response.status)
                {
                  if (callback) callback(response.data);
                }
                else
                {
                  if (Shorty.Debug) Shorty.Debug.log(Shorty.Debug.dump(response.data));
                }
              }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Meta.get
    }, // Shorty.WUI.Meta
    // ===== Shorty.WUI.Sums =====
    Sums:
    {
      // ===== Shorty.WUI.Sums.fill =====
      fill: function()
      {
        var dfd = new $.Deferred();
        $.when
        (
          Shorty.WUI.Sums.get(function(data)
          {
            // update (set) sum values in the control bar
            $('#controls').find('#sum_shortys').text(data.sum_shortys),
            $('#controls').find('#sum_clicks').text(data.sum_clicks)
          })
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Sums.fill
      // ===== Shorty.WUI.Sums.get =====
      get: function(callback)
      {
        var dfd = new $.Deferred();
        $.when(
          $.ajax
          (
            {
              url:     'ajax/count.php',
              cache:   false,
              data:    {},
              success: function(response)
              {
                if ( 'error'==response.status )
                {
                  Shorty.WUI.Notification.show(response.note,'debug');
                }
                else
                {
                  Shorty.WUI.Notification.show(response.note,'info');
                  if (callback) callback(response.data);
                } // if else
              }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // Shorty.WUI.Sums.get
    }, // Shorty.WUI.Sums
  }, // Shorty.WUI

  //==========

  Action:
  {
    // ===== Shorty.Action.Url =====
    Url:
    {
      // ===== Shorty.Action.Url.add =====
      add:function(event,callback)
      {
        var dfd = new $.Deferred();
        var target = $('#dialog-add').find('#target').val() || '';
        var title  = $('#dialog-add').find('#title').val()  || '';
        var notes  = $('#dialog-add').find('#notes').val()  || '';
        var until  = $('#dialog-add').find('#until').val()  || '';
        $.when(
          Shorty.WUI.Notification.hide(),
          $.ajax
          (
            {
              url:     'ajax/add.php',
              cache:   false,
              data:    { target: encodeURIComponent(target),
                        title:  encodeURIComponent(title),
                        notes:  encodeURIComponent(notes),
                        until:  encodeURIComponent(until) },
              error:function(){if (Shorty.Debug) Shorty.Debug.log(this.data);},
              success: function(response)
              {
                if ( 'success'==response.status )
                {
                  Shorty.WUI.Notification.show(response,'info');
                  // close and neutralize dialog
                  if (callback) callback();
                  $('#dialog-add').find('.shorty-input').val('');
                  // add shorty to existing list
                  var f_add_shorty = function(){Shorty.WUI.List.add([response.data],true);};
                  Shorty.WUI.List.toggle(true,f_add_shorty);
                } // if !error
                else
                {
                  Shorty.WUI.Notification.show(response.note,'error');
                }
                $('#dialog-add').slideToggle();
              }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // ===== Shorty.Action.Url.add =====
      // ===== Shorty.Action.Url.edit =====
      edit: function(event)
      {
        var dfd = new $.Deferred();
        var key    = $('#shorty-add-key').val();
        var source = $('#dialog-edit').find('#source').val();
        var target = $('#dialog-edit').find('#target').val();
        var notes  = $('#dialog-edit').find('#notes').val();
        var until  = $('#dialog-edit').find('#until').val();
        $.when(
          Shorty.WUI.Notification.hide(),
          $.ajax
          (
            {
              url:     'ajax/edit.php',
              cache:   false,
              data:    { key: key,
                        source: encodeURI(source),
                        target: encodeURI(target),
                        notes:  encodeURI(notes),
                        until:  encodeURI(until) },
              success: function()
              {
                $('.shorty-add').slideToggle();
                $('.shorty-add').find('.shorty-input').val('');
                $('#shorty-add-key').val('');

                var record = $('.shorty-single[data-key = "' + key + '"]');
                record.children('.shorty-target:first').text(target);

                var record_notes = record.children('.shorty-notes:first').children('a:first');
                record_notes.attr('href', target);
                record_notes.text(notes);
                record.children('.shorty-until').html(until);
              }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // ===== Shorty.Action.Url.edit =====
      // ===== Shorty.Action.Url.del =====
      del: function(event)
      {
        var dfd = new $.Deferred();
        var record = $(this).parent().parent();
        $.when(
          Shorty.WUI.Notification.hide(),
          $.ajax
          (
            {
              url:     'ajax/del.php',
              cache:   false,
              data:    { url: encodeURI($(this).parent().parent().children('.shorty-url:first').text()) },
              success: function(data){ record.animate({ opacity: 'hide' }, 'fast'); }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // ===== Shorty.Action.Url.del =====
      // ===== Shorty.Action.Url.show =====
      show: function(event)
      {
        var dfd = new $.Deferred();
        var record = $(this).parent().parent();
        $('#shorty-add-key').val(record.attr('data-key'));
        $('#shorty-add-source').val(record.children('.shorty-source:first').text());
        $('#shorty-add-target').val(record.children('.shorty-target:first').text());
        $('#shorty-add-notes').val(record.children('.shorty-notes:first').text());
        $('#shorty-add-until').val(record.children('.shorty-until:first').text());
        $.when(
          Shorty.WUI.Notification.hide(),
          function()
          {
            if ($('.shorty-add').css('display') == 'none')
            {
              $('.shorty-add').slideToggle();
            }
          },
          $('html, body').animate({ scrollTop: $('.shorty-menu').offset().top }, 500)
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // ===== Shorty.Action.Url.show =====
      // ===== Shorty.Action.Url.click =====
      click: function(event)
      {
        var dfd = new $.Deferred();
        $.when(
          Shorty.WUI.Notification.hide(),
          $.ajax
          (
            {
              url:     'ajax/click.php',
              cache:   false,
              data:    { url: encodeURI($(this).attr('href')) },
              success: function()
              {
                // increment total number of shortys and clicks (sums)
                var label = $('#controls').find('#sum_clicks');
                label.val(label.val()+1);
              }
            }
          )
        ).then(function(){dfd.resolve();});
        return dfd.promise();
      }, // ===== Shorty.Action.Url.click =====
    }, // ===== Shorty.Action.Url =====
  }, // Shorty.Action

} // Shorty
