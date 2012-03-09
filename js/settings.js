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
 * @brief Client side activity initialization for the system settings dialog
 * @author Christian Reiner
 */

$(document).ready(
  function(){
    // initialize example that depends on backend-base
    if ($('#shorty').find('#backend-static').find('#backend-static-base-system').val().length)
      $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-static').find('#backend-static-base-system').val()+'<shorty key>');
    // modify example upon input of a base
    $('#shorty').find('#backend-static').find('#backend-static-base-system').bind('input',function(){
      $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-static').find('#backend-static-base-system').val()+'<shorty key>');
    });
    // store setting
    $('#shorty').find('#backend-static').find('#backend-static-base-system').focusout(function(){
      // modify example
      $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-static').find('#backend-static-base-system').val()+'<shorty key>');
      // save setting
//      Shorty.Action.Setting.set($('#shorty').find('#backend-static').find('#backend-static-base-system').serialize());
      $.when(
        Shorty.Action.Setting.set($('#shorty').find('#backend-static').find('#backend-static-base-system').serialize())
      ).done(function(result){
        alert(result);
      });
    });
  }
);
