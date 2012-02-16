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

  // ==========
  WUI:
  {
    toggleControls: function()
    {
      var dfd = new $.Deferred();
      Shorty.WUI.hideNotification();
      // show or hide dialog
      var controls = $('#controls');
      if ( ! controls.is(':visible'))
      {
        $.when(
          $.when(
            controls.slideDown('slow')
          ).then(Shorty.WUI.sumsFill)
        ).then(function(){dfd.resolve();});
      }
      else
      {
        $.when(
          controls.slideUp('fast')
        ).then(function(){dfd.resolve();});
      }
      return dfd.promise();
    }, // toggleControls
    toggleDialog: function(dialog)
    {
      var duration = 'slow';
      var easing   = 'swing';
      var dfd = new $.Deferred();
      Shorty.WUI.hideNotification();
      // show or hide dialog
      if ( ! dialog.is(':visible'))
      {
        // start sequence by hiding the desktop first
        $('#desktop').fadeOut(duration,function()
          {
            // reset dialog fields
            $.each(dialog.find('.shorty-input'),function(){if($(this).is('[data]'))$(this).val($(this).attr('data'));});
            $.each(dialog.find('.shorty-value'),function(){if($(this).is('[data]'))$(this).text($(this).attr('data'));});
            $.each(dialog.find('.shorty-icon'), function(){if($(this).is('[data]'))$(this).attr('src',$(this).attr('data'));});
            // show dialog
            dialog.slideDown(duration);
            // initialize dialog
            switch(dialog.attr('id'))
            {
              case 'dialog-add':
                dialog.find('#confirm').bind('click', {dialog: dialog}, function(event){Shorty.WUI.submitDialog(event.data.dialog);} );
                dialog.find('#target').bind('focusout', {dialog: dialog}, function(event){Shorty.WUI.collectMeta(event.data.dialog);} );
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
    }, // toggleDialog
  //WUI:
    toggleDesktopHourglass: function(show)
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
    }, // toggleDesktopHourglass
  //WUI:
    toggleUrlList: function(filled)
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
    }, // toggleUrlList
  //WUI:
    listBuild: function()
    {
      var dfd = new $.Deferred();
      // prepare loading
      $.when
      (
        Shorty.WUI.hideNotification(),
        Shorty.WUI.toggleDesktopHourglass(true),
        Shorty.Action.listGet(function(list){
          Shorty.WUI.listFill(list,function(){
            Shorty.WUI.toggleDesktopHourglass(false);
          });
        })
      ).then(function(){dfd.resolve();});
      return dfd.promise();
    }, // listBuild
  //WUI:
    sumsFill: function()
    {
      var dfd = new $.Deferred();
      $.when
      (
        Shorty.Action.sumsGet(function(data)
        {
          // update (set) sum values in the control bar
          $('#controls').find('#sum_shortys').text(data.sum_shortys),
          $('#controls').find('#sum_clicks').text(data.sum_clicks)
        })
      ).then(function(){dfd.resolve();});
      return dfd.promise();
    }, // sumsFill
  //WUI:
    listFill: function(list,callback)
    {
      var dfd = new $.Deferred();
      if ( ! list.length )
      {
        // list empty, show placeholder instead of empty table
        $.when(Shorty.WUI.toggleUrlList(false)).then(function(){dfd.resolve();});
      }
      else
      {
        // list non-empty, fill and show table instead of placeholder
        // prevent clicks whilst loading the list
        $.when(
          $('.shorty-link').unbind('click', Shorty.Action.urlClick),
          $('.shorty-edit').unbind('click', Shorty.Action.urlEdit),
          $('.shorty-delete').unbind('click', Shorty.Action.urlDel),
          Shorty.WUI.sumsFill(),
          Shorty.WUI.listAdd(list),
          // display the list
          Shorty.WUI.toggleUrlList(true),
          // reenable clicks after loading the list
          $('.shorty-link').click(Shorty.Action.urlClick),
          $('.shorty-edit').click(Shorty.Action.urlEdit),
          $('.shorty-del').click(Shorty.Action.urlDel)
        ).then(function(){dfd.resolve();});
      }
      if (callback) callback();
      return dfd.promise();
    }, // listFill
  //WUI:
    listAdd: function(list,smooth)
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
    }, // listAdd
  //WUI:
    emptyDesktop: function()
    {
      var dfd = new $.Deferred();
      $.when(
        $('#desktop').empty(),
        Shorty.WUI.hideNotification
      ).then(function(){dfd.resolve();});
      return dfd.promise();
    }, // emptyDesktop
  //WUI:
    hideNotification: function()
    {
      var dfd = new $.Deferred();
      $.when(
        $('#notification').fadeOut('fast').text('')
      ).then(function(){dfd.resolve();});
      return dfd.promise();
    }, // hideNotification
  //WUI:
    showNotification: function(message,level)
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
    }, // showNotification
  //WUI:
    collectMeta: function(dialog)
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
      // fill in fallback scheme if none is specified
      if ( ! Shorty.Utility.hasScheme(target) )
      {
        target = 'http://' + target;
        dialog.find('#target').val(target);
      }
      // query meta data from target
      Shorty.Action.metaGet(target,function(meta)
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
    }, // collectMeta
  //WUI:
    submitDialog: function(dialog)
    {
      var dfd = new $.Deferred();
      switch ( dialog.attr('id') )
      {
        case 'dialog-add':
          $.when(
            Shorty.WUI.hideNotification(),
            Shorty.Action.urlAdd(Shorty.WUI.toggleDialog(dialog))
          ).then(function(){dfd.resolve();});
          break;
        case 'dialog-edit':
          $.when(
            Shorty.WUI.hideNotification(),
            Shorty.Action.urlEdit(Shorty.WUI.toggleDialog(dialog))
          ).then(function(){dfd.resolve();});
          break;
        case 'dialog-del':
          $.when(
            Shorty.WUI.hideNotification(),
            Shorty.Action.urlDel(Shorty.WUI.toggleDialog(dialog))
          ).then(function(){dfd.resolve();});
          break;
        default:
          dfd.resolve();
      }; // switch
      return dfd.promise();
    }, // submitDialog
  },// WUI

  //==========

  Action:
  {
    sumsGet: function(callback)
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
                Shorty.WUI.showNotification(response.note,'debug');
              }
              else
              {
                Shorty.WUI.showNotification(response.note,'info');
                if (callback) callback(response.data);
              } // if else
            }
          }
        )
      ).then(function(){dfd.resolve();});
      return dfd.promise();
    }, // sumsGet
  //Action:
    listGet: function(callback)
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
                Shorty.WUI.showNotification(response.note,'debug');
              }
              else
              {
                Shorty.WUI.showNotification(response.note,'info');
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
    }, // listGet
  //Action:
    urlAdd:function(event,callback)
    {
      var dfd = new $.Deferred();
      var target = $('#dialog-add').find('#target').val() || '';
      var title  = $('#dialog-add').find('#title').val()  || '';
      var notes  = $('#dialog-add').find('#notes').val()  || '';
      var until  = $('#dialog-add').find('#until').val()  || '';
      $.when(
        Shorty.WUI.hideNotification(),
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
                Shorty.WUI.showNotification(response,'info');
                // close and neutralize dialog
                if (callback) callback();
                $('#dialog-add').find('.shorty-input').val('');
                // add shorty to existing list
                var f_add_shorty = function(){Shorty.WUI.listAdd([response.data],true);};
                Shorty.WUI.toggleUrlList(true,f_add_shorty);
              } // if !error
              else
              {
                Shorty.WUI.showNotification(response.note,'error');
              }
              $('#dialog-add').slideToggle();
            }
          }
        )
      ).then(function(){dfd.resolve();});
      return dfd.promise();
    }, // urlAdd
  //Action:
    urlEdit: function(event)
    {
      var dfd = new $.Deferred();
      var key    = $('#shorty-add-key').val();
      var source = $('#dialog-edit').find('#source').val();
      var target = $('#dialog-edit').find('#target').val();
      var notes  = $('#dialog-edit').find('#notes').val();
      var until  = $('#dialog-edit').find('#until').val();
      $.when(
        Shorty.WUI.hideNotification(),
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
    }, // urlEdit
  //Action:
    urlDel: function(event)
    {
      var dfd = new $.Deferred();
      var record = $(this).parent().parent();
      $.when(
        Shorty.WUI.hideNotification(),
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
    }, // urlDel
  //Action:
    urlShow: function(event)
    {
      var dfd = new $.Deferred();
      var record = $(this).parent().parent();
      $('#shorty-add-key').val(record.attr('data-key'));
      $('#shorty-add-source').val(record.children('.shorty-source:first').text());
      $('#shorty-add-target').val(record.children('.shorty-target:first').text());
      $('#shorty-add-notes').val(record.children('.shorty-notes:first').text());
      $('#shorty-add-until').val(record.children('.shorty-until:first').text());
      $.when(
        Shorty.WUI.hideNotification(),
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
    }, // urlShow
  //Action:
    urlClick: function(event)
    {
      var dfd = new $.Deferred();
      $.when(
        Shorty.WUI.hideNotification(),
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
    }, // urlClick
  //Action:
    metaGet: function(target,callback)
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
    }, // metaGet
  }, // Shorty.Action

  //==========

  Utility:
  {
    updateBottom: function()
    {
      //check wether user is on bottom of the page
      if ($('body').height() <= ( $(window).height() + $(window).scrollTop()) )
      {
        Shorty.Action.UrlsGet();
      }
    }, // updateBottom
  //Utility:
    hasScheme: function(url)
    {
      var regexp = /^[a-zA-Z0-9]+\:\//;
      return regexp.test(url);
    } // hasScheme
  }, // Shorty.Utility

} // Shorty
