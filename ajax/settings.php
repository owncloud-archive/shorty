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
 * @file ajax/preferences.php
 * @brief Ajax method to store one or more system settings  (plugin settings)
 * @param backend-static-base (string) Url to use as a base when the static backend is active (plugins default, may be overridden by user preference)
 * @returns (json) success/error state indicator
 * @returns (json) Associative array holding the stored values by their key
 * @returns (json) Human readable message describing the result
 * @author Christian Reiner
 */

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkAdminUser ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  $data = array();
  switch ( $_SERVER['REQUEST_METHOD'] )
  {
    case 'POST':
      // detect provided preferences
      $data = array();
      foreach (array_keys($_POST) as $key)
        if ($type=OC_Shorty_Type::$SETTING[$key])
          $data[$key] = OC_Shorty_Type::req_argument ( $key, $type, FALSE );
      // eliminate settings not explicitly set
      $data = array_diff ( $data, array(FALSE) );
      // store settings one by one
      foreach ( $data as $key=>$val )
        OC_Appconfig::setValue( 'shorty', $key, $val );
      break;
    case 'GET':
      // detect requested preferences
      foreach (array_keys($_GET) as $key)
      {
        if (  ('_'!=$key) // ignore ajax timestamp argument
            &&($type=OC_Shorty_Type::$PREFERENCE[$key]) )
        {
          $data[$key] = OC_Preferences::getValue( OC_User::getUser(), 'shorty', $key);
          // morph value into an explicit type
          switch ($type)
          {
            case OC_Shorty_Type::KEY:
            case OC_Shorty_Type::STATUS:
            case OC_Shorty_Type::SORTKEY:
            case OC_Shorty_Type::SORTVAL:
            case OC_Shorty_Type::STRING:
            case OC_Shorty_Type::URL:
            case OC_Shorty_Type::DATE:
              settype ( $data[$key], 'string' );
              break;
            case OC_Shorty_Type::INTEGER:
            case OC_Shorty_Type::TIMESTAMP:
              settype ( $data[$key], 'integer' );
              break;
            case OC_Shorty_Type::FLOAT:
              settype ( $data[$key], 'float' );
              break;
            default:
          } // switch
        }
      } // foreach
      break;
    default:
      throw new OC_Shorty_Exception ( "unexpected request method '%s'", $_SERVER['REQUEST_METHOD'] );
  } // switch
    // a friendly reply, in case someone is interested
  OC_JSON::success ( array ( 'data'    => $data,
                             'message' => OC_Shorty_L10n::t('Setting saved.') ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
