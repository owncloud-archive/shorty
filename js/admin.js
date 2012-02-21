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

$(document).ready(
  function(){
    // backend settings, activate hints for currently selected backend
    var type=$('#shorty').find('#backend-type').val()||'';
    if (type.length){
      $('#shorty').find('#backend-'+type).show();
    }
    // initialize example that depends on backend-base
    if ($('#shorty').find('#backend-base').val().length)
      $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-base').val()+'<shorty key>');
    // react with a matching explanation and example url when backend type is chosen
    $('#shorty').find('#backend-type').chosen().change(
      function(){
        var type=$('#shorty').find('#backend-type').val();
        $('#shorty').find('.backend-supplement').hide();
        if (type.length){
          $('#shorty').find('.backend-supplement').filter('#backend-'+type).fadeIn('slow');
          // save setting
          $.get( OC.filePath('shorty', 'ajax', 'settings.php'),
                 $('#shorty').find('#backend-type').serialize(),
                 function(data){
                   //OC.msg.finishedSaving('#shorty .msg', data);
                 });
          return false;
        }
      }
    );
    $('#shorty').find('#backend-base').bind('input',function(){
        $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-base').val()+'<shorty key>');
      });
    $('#shorty').find('#backend-base').focusout(function(){
        // save setting
        $('#shorty').find('#backend-static').find('#example').text($('#shorty').find('#backend-base').val()+'<shorty key>');
        $.get( OC.filePath('shorty', 'ajax', 'settings.php'),
               $('#shorty').find('#backend-base').serialize(),
               function(data){
                 //OC.msg.finishedSaving('#shorty .msg', data);
               });
      }
    );
  }
);
