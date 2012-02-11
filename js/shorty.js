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

$(document).ready
(
  function()
  {
    // basic action buttons
    $('#desktop').find('.shorty-actions').bind('hover',function(){$(this).fadeToggle();});
    $('#controls').find('#add').bind('click',function(){Shorty.WUI.toggleDialog($('#dialog-add'),true)});
    // initialize desktop
    Shorty.WUI.listBuild();
    // ???
    $(window).scroll(Shorty.Action.update_bottom);
  }
); // document.ready

// ==========

Shorty =
{

// ==========
  WUI:
  {
    toggleDialog: function(dialog,duration,easing)
    {
      duration = duration || 'slow';
      easing   = easing   || 'swing';
      Shorty.WUI.hideNotification();
      // show or hide dialog
      if ( ! dialog.is(':visible'))
      {
        // start sequence by hiding the desktop first
        $('#desktop').fadeOut(duration,function()
          {
            // show dialog
            dialog.slideDown(duration);
            // initialize dialog
            switch ( dialog.attr('id') )
            {
              case 'dialog-add':
                dialog.find('#confirm').bind('click', {dialog: dialog}, function(event){Shorty.WUI.submitDialog(event.data.dialog,dialog.slideDown);} );
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
    }, // toggleDialog
  //WUI:
    toggleDesktopHourglass: function(show,callback)
    {
      if (show)
        $('#desktop').find('.shorty-hourglass').fadeIn('fast',callback);
      else
        $('#desktop').find('.shorty-hourglass').fadeOut('slow',callback);
    }, // toggleDesktopHourglass
  //WUI:
    toggleUrlList: function(filled,callback,duration)
    {
      duration = duration || 'slow';
      if (filled)
      {
        $('#desktop').find('#list-empty').hide();
        $('#desktop').find('#list-nonempty').fadeIn(duration);
        if (callback) callback();
      }
      else
      {
        $('#desktop').find('#list-empty').fadeIn(duration);
        $('#desktop').find('#list-nonempty').hide();
        if (callback) callback();
      }
    }, // toggleUrlList
  //WUI:
    listBuild: function()
    {
      // prepare loading
      Shorty.WUI.hideNotification();
      Shorty.WUI.toggleDesktopHourglass(true,function()
      {
        Shorty.Action.listGet(function(list)
        {
          Shorty.WUI.listFill(list,function()
          {
            Shorty.WUI.toggleDesktopHourglass(false);
          } )
        } );
      } );
    }, // listBuild
  //WUI:
    listFill: function(list,callback)
    {
      var count_urls=0;
      var count_clicks=0;
      if ( ! list.length )
      {
        // list empty, show placeholder instead of empty table
        Shorty.WUI.toggleUrlList(false);
      }
      else
      {
        // list non-empty, fill and show table instead of placeholder
        // prevent clicks whilst loading the list
        $('.shorty-link').unbind('click', Shorty.Action.urlClick);
        $('.shorty-delete').unbind('click', Shorty.Action.urlDel);
        $('.shorty-edit').unbind('click', Shorty.Action.urlShow);
        for(var i in list)
        {
          count_urls   += 1;
          count_clicks += parseInt(list[i].clicks);
          Shorty.WUI.listAdd(i,list[i]);
        }
        // reenable clicks after loading the list
        $('.shorty-link').click(Shorty.Action.urlClick);
        $('.shorty-show').click(Shorty.Action.urlShow);
        $('.shorty-edit').click(Shorty.Action.urlEdit);
        $('.shorty-del').click(Shorty.Action.urlDel);
        Shorty.WUI.toggleUrlList(true);
      }
      // update (set) counters in the control bar
      $('#controls').find('#number').text(count_urls);
      $('#controls').find('#clicks').text(count_clicks);
      callback();
    }, // listFill
  //WUI:
    listAdd: function(index,token,smooth)
    {
      // clone dummy row from list: dummy is the only row with an empty id
      var $dummy = $('.shorty-list tr').filter(function(){return (''==$(this).attr('id'));});
      var $row   = $dummy.clone();
      // set row id to entry key
      $row.attr('id',token.key);
      // enhance row with real token values
      $.each(Array('key','source','target','clicks','created','accessed','until','notes'),
             function()
             {
               $row.attr('data-'+this,token[this]);
               $.each($row.find('td'),function(){$(this).text(token[$(this).attr('id')]);});
             }
            );
      $dummy.after($row);
      if (smooth)
           $row.slideIn('slow');
      else $row.attr('style','visible');
    }, // listAdd
  //WUI:
    emptyDesktop: function()
    {
      Shorty.WUI.hideNotification();
      $('#desktop').empty()
    }, // emptyDesktop
  //WUI:
    hideNotification: function(duration)
    {
      duration = duration || 'fast';
      $('#notification').fadeOut(duration);
      $('#notification').text('');
    }, // hideNotification
  //WUI:
    showNotification: function(message,level,duration)
    {
      level    = level    || 'info';
      duration = duration || 'slow';
      switch(level)
      {
        case 'debug':
          // detect debug mode by checking, of function 'debug()' exists
          if ( Shorty.debug )
          {
            Shorty.debug('Debug: '+message);
            $('#notification').fadeOut('fast');
            $('#notification').attr('title', 'debug message');
            $('#notification').text('Debug: '+message);
            $('#notification').fadeIn(duration);
          }
          break;
        case 'error':
          if (Shorty.debug) Shorty.debug('Error: '+message);
          $('#notification').attr('title', 'error message');
          $('#notification').fadeOut('fast');
          $('#notification').text('Error: ' + message);
          $('#notification').fadeIn(duration);
          break;
        default: // 'info'
          if ( message.length )
          {
            if (Shorty.debug) Shorty.debug('Info: '+message);
            $('#notification').fadeOut('fast');
            $('#notification').text(message);
            $('#notification').fadeIn(duration);
          }
          else
          {
            $('#notification').fadeOut('fast');
            $('#notification').text('');
          }
      } // switch
    }, // showNotification
  //WUI:
    collectMeta: function(dialog)
    {
      var target = $('#dialog-add').find('#target').val().trim();
      // don't bother getting active on empty input
      if ( ! target.length )
        return;
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
    }, // collectMeta
  //WUI:
    submitDialog: function(dialog,callback)
    {
      Shorty.WUI.hideNotification();
      switch ( dialog.attr('id') )
      {
        case 'dialog-add':
          Shorty.Action.urlAdd(callback);
          break;
        case 'dialog-edit':
          Shorty.Action.urlEdit(callback);
          break;
        case 'dialog-del':
          Shorty.Action.urlDel(callback);
          break;
      }; // switch
    },
  }, // submitDialog

  //==========

  Action:
  {
    listGet: function(callback)
    {
// ToDo Fixme: correct ids of filter variables
      var target = $('#list-filter-target').val() || '';
      var title  = $('#list-filter-title').val()  || '';
      $.ajax
      (
        {
          url:     'ajax/list.php',
          cache:   false,
          data:    { target: encodeURI(target), title: encodeURI(title) },
          success: function(response)
          {
            if ( 'error'==response.status )
            {
              callback({});
            }
            else
            {
              Shorty.WUI.showNotification(response.note,'info');
              callback(response.data);
            } // if else
          }
        }
      );
    }, // listGet
  //Action:
    urlAdd:function(event,callback)
    {
      Shorty.WUI.hideNotification();
      var target = $('#dialog-add').find('#target').val();
      var title  = $('#dialog-add').find('#title').val();
      var notes  = $('#dialog-add').find('#notes').val();
      var until  = $('#dialog-add').find('#until').val();
      $.ajax
      (
        {
          url:     'ajax/add.php',
          cache:   false,
          data:    { target: encodeURIComponent(target),
                     title:  encodeURIComponent(title),
                     notes:  encodeURIComponent(notes),
                     until:  encodeURIComponent(until) },
//error:function(){alert('e');},
          success: function(response)
          {
            if ( 'success'==response.status )
            {
              Shorty.WUI.showNotification(response,'info');
              // close and neutralize dialog
              if (callback) callback();
              $('#dialog-add').find('.shorty-input').val('');
              // add shorty to existing list
              var f_add_shorty = function(){Shorty.WUI.listAdd(0,response.data,true);};
              Shorty.WUI.toggleUrlList(true,f_add_shorty);
            } // if !error
            else
            {
              Shorty.WUI.showNotification(response.note,'error');
            }
            $('#dialog-add').slideToggle();
          }
        }
      );
    }, // urlAdd
  //Action:
    urlEdit: function(event)
    {
      Shorty.WUI.hideNotification();
      var key    = $('#shorty-add-key').val();
      var source = $('#dialog-edit').find('#source').val();
      var target = $('#dialog-edit').find('#target').val();
      var notes  = $('#dialog-edit').find('#notes').val();
      var until  = $('#dialog-edit').find('#until').val();
      $.ajax
      (
        {
          url:     'ajax/edit.php',
          cache:   false,
          data:    'key=' + key + '&source=' + encodeURI(source) + '&target=' + encodeURI(target) + '&notes=' + encodeURI(notes) + '&until=' + encodeURI(until),
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
      );
    }, // urlEdit
  //Action:
    urlDel: function(event)
    {
      Shorty.WUI.hideNotification();
      var record = $(this).parent().parent();
      $.ajax
      (
        {
          url:     'ajax/del.php',
          cache:   false,
          data:    'url=' + encodeURI($(this).parent().parent().children('.shorty-url:first').text()),
          success: function(data){ record.animate({ opacity: 'hide' }, 'fast'); }
        }
      );
    }, // urlDel
  //Action:
    urlShow: function(event)
    {
      Shorty.WUI.hideNotification();
      var record = $(this).parent().parent();
      $('#shorty-add-key').val(record.attr('data-key'));
      $('#shorty-add-source').val(record.children('.shorty-source:first').text());
      $('#shorty-add-target').val(record.children('.shorty-target:first').text());
      $('#shorty-add-notes').val(record.children('.shorty-notes:first').text());
      $('#shorty-add-until').val(record.children('.shorty-until:first').text());
      if ($('.shorty-add').css('display') == 'none')
      {
        $('.shorty-add').slideToggle();
      }
      $('html, body').animate({ scrollTop: $('.shorty-menu').offset().top }, 500);
    }, // urlShow
  //Action:
    urlClick: function(event)
    {
      Shorty.WUI.hideNotification();
      $.ajax
      (
        {
          url:     'ajax/click.php',
          cache:   false,
          data:    { url: encodeURI($(this).attr('href')) },
          success: function()
          {
            // increment shorties and total number of clicks
            $('#controls').find('#clicks').val($('#controls').find('#clicks').val()+1);
          }
        }
      );
    }, // urlClick
  //Action:
    metaGet: function(target,callback)
    {
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
              callback(response.data);
            }
            else
            {
              if (Shorty.Debug) Shorty.Debug.log(Shorty.Debug.dump(response.data));
            }
          }
        }
      );
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
