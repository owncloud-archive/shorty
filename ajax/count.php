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
 * @file ajax/count.php
 * @brief Ajax method to retrieve a list of important sums, counts of the existing set of shortys
 * @returns (json) success/error state indicator
 * @returns (json) Associative array of counts
 * @author Christian Reiner
 */

//no apps or filesystem
$RUNTIME_NOSETUPFS = TRUE;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkLoggedIn ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  $param = array
  (
    ':user'   => OC_User::getUser ( ),
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_COUNT );
  $result = $query->execute($param);
  $reply = $result->fetchAll();
  OC_JSON::success ( array ( 'data'    => $reply[0],
                             'message' => '' ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
