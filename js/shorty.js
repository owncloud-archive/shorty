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

var shorty_debug        = true;
var shorty_page         = 0;

$(document).ready
(
  function()
  {
    $('#desktop').find('.shorty-actions').hover(function(){$(this).fadeToggle();});
    $('#controls').find('#add').click(Shorty.WUI.toggleDialogAdd);
    $('#dialog-add').find('#confirm').click(Shorty.WUI.submitDialogAdd);
    $('#dialog-edit').find('#confirm').click(Shorty.WUI.submitDialogEdit);
    $(window).scroll(Shorty.Action.update_bottom);
    Shorty.Action.listGet();
  }
);

Shorty={

  WUI:{
    toggleDialogAdd:function(duration,easing)
    {
      duration = duration || 'slow';
      easing   = easing   || 'swing';
      Shorty.WUI.hideNotification();
      Shorty.WUI.toggleDesktopShading(duration,easing);
      $('#dialog-add').slideToggle().find('#target').focus();
    },
  //WUI:
    toggleDialogEdit:function(duration,easing)
    {
      duration = duration || 'slow';
      easing   = easing   || 'swing';
      Shorty.WUI.hideNotification();
      Shorty.WUI.toggleDesktopShading(duration,easing);
      $('#dialog-edit').slideToggle().find('#title').focus();
    },
  //WUI:
    toggleDesktopShading:function(duration,easing,callback)
    {
      duration = duration || 'slow';
      easing   = easing   || 'swing';
      $('#desktop').find('.shorty-shading').fadeToggle(duration,easing,callback);
    },
  //WUI:
    toggleDesktopHourglass:function(active)
    {
      if (active)
        $('#desktop').find('.shorty-hourglass').fadeIn('fast');
      else
        $('#desktop').find('.shorty-hourglass').fadeOut('slow');
    },
  //WUI:
    toggleUrlList:function(filled,duration)
    {
      duration = duration || 'slow';
      if (filled)
      {
        $('#desktop').find('#list-empty').hide();
        $('#desktop').find('#list-nonempty').fadeIn(duration);
      }
      else
      {
        $('#desktop').find('#list-empty').fadeIn(duration);
        $('#desktop').find('#list-nonempty').hide();
      }
    },
  //WUI:
    emptyDesktop:function()
    {
      Shorty.WUI.hideNotification();
      $('#desktop').empty()
    },
  //WUI:
    hideNotification:function(duration)
    {
      duration = duration || 'fast';
      $('#notification').fadeOut(duration);
      $('#notification').text('');
    },
  //WUI:
    showNotification:function(response,duration)
    {
      duration = duration || 'slow';
      if ( shorty_debug && ('error'==response.status ) )
      {
        $('#notification').attr('title', response.title);
        $('#notification').text(response.message);
        $('#notification').fadeIn(duration);
      }
      else if ( 'success'==response.status )
      {
        if ( ''!=response.note )
        {
          $('#notification').text(response.note);
          $('#notification').fadeIn(duration);
        }
        else
        {
          $('#notification').fadeOut('fast');
          $('#notification').text('');
        }
      }
    },
  //WUI:
    submitDialogAdd:function()
    {
      Shorty.WUI.hideNotification();
      Shorty.Action.urlAdd();
      Shorty.WUI.toggleDesktopShading();
    },
  //WUI:
    submitDialogEdit:function()
    {
      Shorty.WUI.hideNotification();
      Shorty.Action.urlEdit();
      Shorty.WUI.toggleDesktopShading();
    },
  },

  //==========

  Action:{
    listGet:function()
    {
      if ( 'visible'==$('#desktop').find('.shorty-hourglass').css('display') )
      {
        // patience... list already loading...
        return;
      }
      // prepare loading
      Shorty.WUI.hideNotification();
      Shorty.WUI.toggleDesktopHourglass(true);
      var target = Shorty.Utility.encodeEntities($('#list-filter-target').val());
      var title  = Shorty.Utility.encodeEntities($('#list-filter-title').val());
      // load current list
      $.ajax
      (
        {
          url: 'ajax/list.php',
          data: 'target=' + encodeURI(target) + '&title=' + encodeURI(title),
          success: function(response)
          {
            if ( 'error'==response.status )
            {
              Shorty.WUI.toggleDesktopHourglass(false);
              Shorty.WUI.showNotification ( response )
            }
            else
            {
//              if ( strlen(response.message) )
                Shorty.WUI.showNotification ( response );
              if ( 0==response.data.length )
              {
                // list empty, show placeholder instead of empty table
                $('#controls').find('#number').text(0);
                $('#controls').find('#clicks').text(0);
                Shorty.WUI.toggleUrlList(false);
              }
              else
              {
                // list non-empty, fill and show table instead of placeholder
                // prevent clicks whilst loading the list
                $('.shorty-link').unbind('click', Shorty.Action.urlClick);
                $('.shorty-delete').unbind('click', Shorty.Action.urlDel);
                $('.shorty-edit').unbind('click', Shorty.Action.urlShow);
                var count_urls=0;
                var count_clicks=0;
                for(var i in response.data) {
                  count_urls   += 1;
  //                count_clicks += response.data[i].clicks;
                  count_clicks += 0;
                  Shorty.Action.listAdd(i,response.data[i]);
                }
                $('#controls').find('#number').text(count_urls);
                $('#controls').find('#clicks').text(count_clicks);
                // reenable clicks after loading the list
                $('.shorty-link').click(Shorty.Action.urlClick);
                $('.shorty-delete').click(Shorty.Action.urlDel);
                $('.shorty-edit').click(Shorty.Action.urlShow);
                Shorty.WUI.toggleUrlList(true);
              }
              Shorty.WUI.toggleDesktopHourglass(false);
            } // if else
          }
        }
      );
    },
  //Action:
    listAdd:function(index,token)
    {
      // clone dummy row from list: dummy is the only row with an empty id
//      var $dummy = $('#url-list-body tr').filter(function(){return (''==$(this).attr('id'));});
      var $dummy = $('.shorty-list tr').filter(function(){return (''==$(this).attr('id'));});
      var $row   = $dummy.clone();
      // set row id to entry key
      $row.attr('id',token.key);
      // enhance row with real token values
      $.each(Array('key','source','target','clicks','created','accessed','until','notes'),
             function()
             {
               $row.attr('data-'+this,token[this]);
//               $row.find('td').filter(function(aspect){return ('shorty-'+aspect==$(this).attr('id'));}).text(token[this]);
               $.each($row.find('td'),function(){$(this).text(token[$(this).attr('id')]);});
             }
            );
      $dummy.after($row);
      $row.attr('style','visible');
    },
  //Action:
    urlAdd:function(event)
    {
      Shorty.WUI.hideNotification();
      var target = $('#dialog-add').find('#target').val();
      var title  = $('#dialog-add').find('#title').val();
      var notes  = $('#dialog-add').find('#notes').val();
      var until  = $('#dialog-add').find('#until').val();
      $.ajax
      (
        {
          url: 'ajax/add.php',
          data: 'target=' + encodeURIComponent(target) 
              + '&title=' + encodeURIComponent(title) 
              + '&notes=' + encodeURIComponent(notes) 
              + '&until=' + encodeURIComponent(until),
          success: function(response)
          {
            Shorty.WUI.showNotification ( response )
            if ( 'error'!=response.status )
            {
              $('#dialog-add').find('.shorty-input').val('');
              Shorty.Action.listAdd(0,response.data);
              Shorty.WUI.toggleUrlList(true);
              Shorty.WUI.toggleDesktopHourglass(false);
            } // if !error
            $('#dialog-add').slideToggle();
          }
        }
      );
    },
  //Action:
    urlEdit:function(event)
    {
      Shorty.WUI.hideNotification();
      var key    = $('#shorty-add-key').val();
      var source = Shorty.Utility.encodeEntities($('#shorty-add-source').val());
      var target = Shorty.Utility.encodeEntities($('#shorty-add-target').val());
      var notes  = Shorty.Utility.encodeEntities($('#shorty-add-notes').val());
      var until  = Shorty.Utility.encodeEntities($('#shorty-add-until').val());
      $.ajax
      (
        {
          url: 'ajax/edit.php',
          data: 'key=' + key + '&source=' + encodeURI(source) + '&target=' + encodeURI(target) + '&notes=' + encodeURI(notes) + '&until=' + encodeURI(until),
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
    },
  //Action:
    urlDel:function(event)
    {
      Shorty.WUI.hideNotification();
      var record = $(this).parent().parent();
      $.ajax
      (
        {
          url: 'ajax/del.php',
          data: 'url=' + encodeURI($(this).parent().parent().children('.shorty-url:first').text()),
          success: function(data){ record.animate({ opacity: 'hide' }, 'fast'); }
        }
      );
    },
  //Action:
    urlShow:function(event)
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
    },
  //Action:
    urlClick:function(event)
    {
      Shorty.WUI.hideNotification();
      $.ajax
      (
        {
          url: 'ajax/click.php',
          data: 'url=' + encodeURI($(this).attr('href')),
        }
      );
    },
  },

  //==========

  Utility:{
    updateBottom:function()
    {
      //check wether user is on bottom of the page
      if ($('body').height() <= ($(window).height() + $(window).scrollTop()))
      {
        Shorty.Action.UrlsGet();
      }
    },
  //Utility:
    encodeEntities:function(s)
    {
      try
      {
        return $('<div/>').text(s).html();
      } catch (ex)
      {
        return "";
      }
    },
  //Utility:
    hasProtocol:function(url)
    {
      var regexp = /(http|https|ftp|sftp|ftps)/;
      return regexp.test(url);
    }
  }
}
