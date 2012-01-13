/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011 Christian Reiner <foss@christian-reiner.info>
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
    $('#controls_button_add').click(Shorty.WUI.toggleDialogAdd);
    $('#dialog_add_submit').click(Shorty.WUI.submitDialogAdd);
    $('.shorty-actions').hover(function(){$(this).fadeToggle();});
    $(window).scroll(Shorty.Action.update_bottom);
    Shorty.Action.listGet();
  }
);

Shorty={
//WUI
  WUI:{
    toggleDialogAdd:function()
    {
      Shorty.WUI.toggleDesktopShading();
      Shorty.WUI.hideNotification();
      $('#dialog_add').slideToggle();
      $('#dialog_add_target').focus();
    },
    submitDialogAdd:function()
    {
      Shorty.WUI.hideNotification();
      Shorty.Action.urlAdd();
      Shorty.WUI.toggleDesktopShading();
    },
    toggleDialogEdit:function()
    {
      Shorty.WUI.toggleDesktopShading();
      Shorty.WUI.hideNotification();
      $('#dialog_edit').slideToggle();
    },
    submitDialogEdit:function()
    {
      Shorty.WUI.hideNotification();
      Shorty.Action.urlEdit();
      Shorty.WUI.toggleDesktopShading();
    },
    toggleDesktopShading:function()
    {
      $('#desktop').children('.shorty-shading').fadeToggle();
    },
    toggleDesktopHourglass:function(active)
    {
      if (active)
        $('#desktop').children('.shorty-hourglass').fadeIn();
      else
        $('#desktop').children('.shorty-hourglass').fadeOut('slow');
    },
    emptyDesktop:function()
    {
      Shorty.WUI.hideNotification();
      $('#desktop').empty()
    },
    setControlsLabel:function(id,value)
    {
      $(id).text(value);
    },
    hideNotification:function()
    {
      $('#notification').fadeOut('fast');
      $('#notification').text(t('shorty',''));
    },
    showNotification:function ( response )
    {
      if ( shorty_debug && ('error'==response.status ) )
      {
        $('#notification').attr('title', response.title);
        $('#notification').text(response.message);
        $('#notification').fadeIn();
      }
      else if ( 'success'==response.status )
      {
        if ( ''!=response.note )
        {
          $('#notification').text(response.note);
          $('#notification').fadeIn();
        }
        else
        {
          $('#notification').fadeOut();
          $('#notification').text('');
        }
      }
    }
  },
//Action:
  Action:{
    listGet:function()
    {
      if ( 'visible'==$('#desktop').children('.shorty-hourglass').css('display') )
      {
        // patience... list already loading...
        return;
      }
      // prepare loading
      Shorty.WUI.hideNotification();
      Shorty.WUI.toggleDesktopHourglass(true);
      var target = Shorty.Utility.encodeEntities($('#list_filter_target').val());
      var notes  = Shorty.Utility.encodeEntities($('#list_filter_notes').val());
      // load current list
      $.ajax
      (
        {
          url: 'ajax/list.php',
          data: 'target=' + encodeURI(target) + '&notes=' + encodeURI(notes),
          success: function(response)
          {
            if ( 'error'==response.status )
            {
              Shorty.WUI.toggleDesktopHourglass(false);
              Shorty.WUI.showNotification ( response )
            }
            else
            {
              Shorty.WUI.showNotification ( response );
              // prevent clicks whilst loading the list
              $('.shorty_link').unbind('click', Shorty.Action.urlClick);
              $('.shorty_delete').unbind('click', Shorty.Action.urlDel);
              $('.shorty_edit').unbind('click', Shorty.Action.urlShow);
//              $.each(response, function(index, value) { Shorty.ListAdd(index,value); }
              var count_urls=0;
              var count_clicks=0;
              for(var i in response.data) {
                count_urls   += 1;
//                count_clicks += response.data[i].clicks;
                count_clicks += 0;
                Shorty.Action.listAdd(i,response.data[i]);
              }
              Shorty.WUI.setControlsLabel('#controls_label_number',count_urls);
              Shorty.WUI.setControlsLabel('#controls_label_clicks',count_clicks);
              // reenable clicks after loading the list
              $('.shorty_link').click(Shorty.Action.urlClick);
              $('.shorty_delete').click(Shorty.Action.urlDel);
              $('.shorty_edit').click(Shorty.Action.urlShow);
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
//               $row.find('td').filter(function(aspect){return ('shorty_'+aspect==$(this).attr('id'));}).text(token[this]);
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
      var target = $('#dialog_add_target').val();
      var notes  = $('#dialog_add_notes').val();
      var until  = $('#dialog_add_until').val();
      $.ajax
      (
        {
          url: 'ajax/add.php',
          data: 'target=' + encodeURIComponent(target) + '&notes=' + encodeURIComponent(notes) + '&until=' + encodeURIComponent(until),
          success: function(response)
          {
            var shorty_id = response.data;
            $('#dialog_add').slideToggle();
            $('#dialog_add').children('p').children('.shorty_input').val('');
            Shorty.Action.listAdd(response.data);
          }
        }
      );
    },
//Action:
    urlEdit:function(event)
    {
      Shorty.WUI.hideNotification();
      var key    = $('#shorty_add_key').val();
      var source = Shorty.Utility.encodeEntities($('#shorty_add_source').val());
      var target = Shorty.Utility.encodeEntities($('#shorty_add_target').val());
      var notes  = Shorty.Utility.encodeEntities($('#shorty_add_notes').val());
      var until  = Shorty.Utility.encodeEntities($('#shorty_add_until').val());

      $.ajax
      (
        {
          url: 'ajax/edit.php',
          data: 'key=' + key + '&source=' + encodeURI(source) + '&target=' + encodeURI(target) + '&notes=' + encodeURI(notes) + '&until=' + encodeURI(until),
          success: function()
          {
            $('.shorty_add').slideToggle();
            $('.shorty_add').children('p').children('.shorty_input').val('');
            $('#shorty_add_key').val('');

            var record = $('.shorty_single[data-key = "' + key + '"]');
            record.children('.shorty_target:first').text(target);

            var record_notes = record.children('.shorty_notes:first').children('a:first');
            record_notes.attr('href', target);
            record_notes.text(notes);
            record.children('.shorty_until').html(until);
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
          data: 'url=' + encodeURI($(this).parent().parent().children('.shorty_url:first').text()),
          success: function(data){ record.animate({ opacity: 'hide' }, 'fast'); }
        }
      );
    },
//Action:
    urlShow:function(event)
    {
      Shorty.WUI.hideNotification();
      var record = $(this).parent().parent();
      $('#shorty_add_key').val(record.attr('data-key'));
      $('#shorty_add_source').val(record.children('.shorty_source:first').text());
      $('#shorty_add_target').val(record.children('.shorty_target:first').text());
      $('#shorty_add_notes').val(record.children('.shorty_notes:first').text());
      $('#shorty_add_until').val(record.children('.shorty_until:first').text());
      if ($('.shorty_add').css('display') == 'none')
      {
        $('.shorty_add').slideToggle();
      }
      $('html, body').animate({ scrollTop: $('.shorty_menu').offset().top }, 500);
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
//Utility:
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
