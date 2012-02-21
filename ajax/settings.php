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
  // detect settings
  $data = array(
    'backend-type' => OC_Shorty_Type::req_argument('backend-type',OC_Shorty_Type::STRING,FALSE),
    'backend-base' => OC_Shorty_Type::req_argument('backend-base',OC_Shorty_Type::URL,FALSE)
  );
  // store settings
  if ( $data['backend-type'] )
    OC_Appconfig::setValue( 'shorty', 'backend-type', $data['backend-type'] );
  if ( $data['backend-base'] )
    OC_Appconfig::setValue( 'shorty', 'backend-base', $data['backend-base'] );
  OC_JSON::success ( array ( 'data' => $data,
                             'note' => OC_Shorty_L10n::t('Setting saved.') ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
