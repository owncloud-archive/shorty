<?php
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

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkLoggedIn ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  $p_key   = OC_Shorty_Type::req_argument ( 'key',   OC_Shorty_Type::KEY,    TRUE );
  $p_title = OC_Shorty_Type::req_argument ( 'title', OC_Shorty_Type::STRING, FALSE );
  $p_notes = OC_Shorty_Type::req_argument ( 'notes', OC_Shorty_Type::STRING, FALSE );
  $param = array
  (
    'user'  => OC_User::getUser ( ),
    'key'   => $p_key,
    'title' => $p_title,
    'notes' => $p_notes,
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_UPDATE );
  $query->execute ( $param );
  OC_JSON::success ( array ( 'data' => $p_key,
                             'note' => sprintf(OC_Shorty_L10n::t("Modifications for shortened url with key '%s' saved"),$p_key) )  );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
