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
 * @file js/preferences.js
 * @brief Client side activity initialization for the user preferences dialog
 * @author Christian Reiner
 */

$(document).ready(
  function(){
    // backend preferences, activate hints for currently selected backend
    var type=$('#shorty').find('#backend-type').val()||'';
    if (type.length){
      $('#shorty').find('#backend-'+type).show();
    }
    // backend 'static': initialize example that depends on backend-base
    if ($('#shorty').find('#backend-static').find('#backend-static-base').val().length)
      $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-static').find('#backend-static-base').val()+'<shorty key>');
    // backend 'static': offer a clickable example link to verify the correct setup
    $('#shorty').find('#backend-static').find('#example').bind('click',function(){
      Verification.Dialog.init($('#shorty').find('#backend-static').find('#example').text());
    });
    // react with a matching explanation and example url when backend type is chosen
    $('#shorty').find('#backend-type').chosen().change(
      function(){
        var type=$('#shorty').find('#backend-type').val();
        $('#shorty').find('.backend-supplement').hide();
        if (type.length){
          $('#shorty').find('.backend-supplement').filter('#backend-'+type).fadeIn('slow');
          // save preference
          Shorty.Action.Preference.set($('#shorty').find('#backend-type').serialize());
          return false;
        }
      }
    );
    // backend 'static': modify example
    $('#shorty').find('#backend-static').find('#backend-static-base').bind('input',function(){
        $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-static').find('#backend-static-base').val()+'<shorty key>');
      });
    // safe preferences
    $('#shorty').find('.backend-supplement').focusout(function(){
        // save preference
        Shorty.Action.Preference.set($(this).find('input').serialize());
      }
    );
  }
);

// Verification
Verification =
{
  Dialog:
  {
    init:function(target){
      //alert(encodeURIComponent(target));
      var popup=$('#shorty').find('#verification');
      popup.dialog({show:'fade',autoOpen:true,modal:true});
      popup.dialog('option','minHeight',240 );
      popup.dialog('open');
      this.check(popup,$('#shorty').find('#backend-static').find('#example').text());
    }, // Verification::Dialog::init

     // Verification::Dialog::check
    check:function(popup,target){
      $.ajax({
        url:     target,
        cache:   false,
        data:    { },
        error:   function(){
          $.when(popup.find('#hourglass').fadeOut('slow')).then(function(){
            popup.find('#failure').fadeIn('slow');
          });
        },
        success: function(response){
          if ( 'error'==response.status )
            $.when(popup.find('#hourglass').fadeOut('slow')).then(function(){
              popup.find('#failure').fadeIn('slow');
            });
          else
            $.when(popup.find('#hourglass').fadeOut('slow')).then(function(){
              popup.find('#success').fadeIn('slow');
            });
        }
      });
    } // Verification::Dialog::check
  } // Verification::Dialog
} // Verification
