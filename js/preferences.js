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
    var type=$('#shorty #backend-type').val()||'';
    if (type.length){
      $('#shorty #backend-'+type).show();
    }
    // backend 'static': initialize example that depends on backend-base system setting
    if ($('#shorty #backend-static #backend-static-base').val().length)
      $('#shorty #backend-static #example').text($('#shorty #backend-static #backend-static-base').val()+'<shorty key>');
    // backend 'static': offer a clickable example link to verify the correct setup
    $('#shorty #backend-static #example').bind('click',function(){
      Verification.Dialog.init($('#shorty #backend-static #example').text());
    });
    // react with a matching explanation and example url when backend type is chosen
    $('#shorty #backend-type').chosen().change(
      function(){
        var type=$('#shorty #backend-type').val();
        $('#shorty .backend-supplement').hide();
        if (type.length){
          $('#shorty .backend-supplement').filter('#backend-'+type).fadeIn('slow');
          // save preference
          Shorty.Action.Preference.set($('#shorty #backend-type').serialize());
          return false;
        }
      }
    );
    // safe preferences
    $('#shorty .backend-supplement').focusout(function(){
      // save preference
      Shorty.Action.Preference.set($(this).find('input').serialize());
    });
  }
);

// Verification
Verification =
{
  Dialog:
  {
    init:function(target){
      //alert(encodeURIComponent(target));
      var popup=$('#shorty #verification');
      popup.dialog({show:'fade',autoOpen:true,modal:true});
      popup.dialog('option','minHeight',240 );
      popup.dialog('open');
      this.check(popup,$('#shorty #backend-static #example').text());
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
