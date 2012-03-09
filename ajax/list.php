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
 * @file ajax/list.php
 * @brief Ajax method to retrieve a list of existing shortys
 * @returns (json) success/error state indicator
 * @returns (number) Total number of shortys in the list
 * @returns (json) Numeric array of all shortys, associative array of attributes as values for every single shorty contained
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
  define ('PAGE_SIZE', 100);
  $p_offset = OC_Shorty_Type::req_argument ( 'page', OC_Shorty_Type::INTEGER, FALSE) * PAGE_SIZE;
  // pre-sort list according to user preferences
  $p_sort = OC_Shorty_Type::$SORTING[OC_Preferences::getValue(OC_User::getUser(),'shorty','list-sort-key','created')];
  $param = array
  (
    ':user'   => OC_User::getUser ( ),
    ':sort'   => $p_sort,
    ':offset' => $p_offset,
    ':limit'  => PAGE_SIZE,
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_LIST );
  $result = $query->execute($param);
  $reply = $result->fetchAll();
  OC_JSON::success ( array ( 'data'  => $reply,
                             'count' => sizeof($reply),
                             'note'  => OC_Shorty_L10n::t('Number of entries: %s', count($reply)) ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
