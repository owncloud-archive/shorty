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
  function ( )
  {
    $('#shorty-add-submit').click(add_url);
  }
);

function add_url ( event )
{
  var target = $('#shorty-add-url').val();
  var notes  = $('#shorty-add-note').val();
  var until  = $('#shorty-add-until').val();
  $.ajax
  (
    {
      url: 'ajax/add_url.php',
      data: 'target=' + encodeURI(target) + '&notes=' + encodeURI(notes) + '&until=' + encodeURI(until),
      success: function ( data )
      {
        location.href='index.php';
      }
    }
  );
}