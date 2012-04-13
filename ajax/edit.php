<?php
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
 * @file ajax/edit.php
 * @brief Ajax method to modify aspects of an existing shorty
 * @param key (string) Internal key of the referenced shorty
 * @param title (string) Human readable title
 * @param notes (string) Any additional information in free text form
 * @returns (json) success/error state indicator
 * @returns (json) Associative array holding the key of the shorty whose click was registered
 * @author Christian Reiner
 */

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkLoggedIn ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  $p_key     = OC_Shorty_Type::req_argument ( 'key',     OC_Shorty_Type::KEY,    TRUE );
  $p_status  = OC_Shorty_Type::req_argument ( 'status',  OC_Shorty_Type::STATUS, FALSE );
  $p_title   = OC_Shorty_Type::req_argument ( 'title',   OC_Shorty_Type::STRING, FALSE );
  $p_until   = OC_Shorty_Type::req_argument ( 'until',   OC_Shorty_Type::DATE,   FALSE );
  $p_notes   = OC_Shorty_Type::req_argument ( 'notes',   OC_Shorty_Type::STRING, FALSE );
  $param = array
  (
    ':user'  => OC_User::getUser ( ),
    ':key'   => $p_key,
    ':status'=> $p_status  ? $p_status  : '',
    ':title' => $p_title   ? $p_title   : '',
    ':notes' => $p_notes   ? $p_notes   : '',
    ':until' => $p_until,
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_UPDATE );
  $query->execute ( $param );
  
  // read new entry for feedback
  $param = array
  (
    'user' => OC_User::getUser(),
    'key'  => $p_key,
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_VERIFY );
  $entry = $query->execute($param)->FetchAll();
  OC_JSON::success ( array ( 'data'    => $entry[0],
                             'message' => OC_Shorty_L10n::t("Modifications for shorty with key '%s' saved",$p_key) ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
