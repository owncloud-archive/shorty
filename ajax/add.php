<?php
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

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkLoggedIn ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  $p_key     = OC_Shorty_Tools::shorty_key ( );
  $p_title   = OC_Shorty_Type::req_argument ( 'title',   OC_Shorty_Type::STRING, FALSE );
  $p_target  = OC_Shorty_Type::req_argument ( 'target',  OC_Shorty_Type::URL,    TRUE  );
  $p_until   = OC_Shorty_Type::req_argument ( 'until',   OC_Shorty_Type::DATE,   FALSE );
  $p_notes   = OC_Shorty_Type::req_argument ( 'notes',   OC_Shorty_Type::STRING, FALSE );
  $p_favicon = OC_Shorty_Type::req_argument ( 'favicon', OC_Shorty_Type::URL,    FALSE );
  // register shorty at backend
  $p_source = OC_Shorty_Backend::registerUrl ( $p_key );
  // fallback title: choose hostname if no title is specified
  $p_title = parse_url ( $p_target, PHP_URL_HOST );
  // insert new shorty into our database
  $param = array
  (
    ':user'    => OC_User::getUser(),
    ':key'     => $p_key,
    ':title'   => $p_title   ? $p_title   : '',
    ':favicon' => $p_favicon ? $p_favicon : '',
    ':source'  => $p_source  ? $p_source  : '',
    ':target'  => $p_target  ? $p_target  : '',
    ':notes'   => $p_notes   ? $p_notes   : '',
    ':until'   => $p_until,
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_INSERT );
  $query->execute ( $param );

  // read new entry for feedback
  $param = array
  (
    'user' => OC_User::getUser(),
    'key'  => $p_key,
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_VERIFY );
  $entry = $query->execute($param)->FetchAll();
  OC_JSON::success ( array ( 'data' => $entry[0],
                             'note' => OC_Shorty_L10n::t("Url shortened to: %s",$p_source) ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
