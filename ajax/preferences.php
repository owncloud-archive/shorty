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
  $data = array();
  switch ( $_SERVER['REQUEST_METHOD'] )
  {
    case 'POST':
      // detect settings
      $data = array(
        'backend-type'        => OC_Shorty_Type::req_argument ( 'backend-type',        OC_Shorty_Type::STRING,  FALSE ),
        'backend-static-base' => OC_Shorty_Type::req_argument ( 'backend-static-base', OC_Shorty_Type::URL,     FALSE ),
        'backend-google-key'  => OC_Shorty_Type::req_argument ( 'backend-google-key',  OC_Shorty_Type::STRING,  FALSE ),
        'backend-bitly-user'  => OC_Shorty_Type::req_argument ( 'backend-bitly-user',  OC_Shorty_Type::STRING,  FALSE ),
        'backend-bitly-key'   => OC_Shorty_Type::req_argument ( 'backend-bitly-key',   OC_Shorty_Type::STRING,  FALSE ),
        'list-sort-key'       => OC_Shorty_Type::req_argument ( 'list-sort-key',       OC_Shorty_Type::SORTKEY, FALSE ),
      );
      // eliminate settings not explicitly set
      $data = array_diff ( $data, array(FALSE) );
      // store settings
      foreach ( $data as $key=>$val )
        OC_Preferences::setValue( OC_User::getUser(), 'shorty', $key, $val );
      break;
    case 'GET':
      // we simply look for all tokens specified as arguments ( example: http://.../shorty/ajax/preferences.php?pref1&pref2 )
      foreach ( array_keys ($_GET) as $key )
        $data[$key] = OC_Preferences::setValue( OC_User::getUser(), 'shorty', $key );
      break;
    default:
      throw new OC_Shorty_Exception ( "unexpected request method '%s'", $_SERVER['REQUEST_METHOD'] );
  } // switch
  } // switch
  // a friendly reply, in case someone is interested
  OC_JSON::success ( array ( 'data' => $data,
                             'note' => OC_Shorty_L10n::t('Preference saved.') ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
