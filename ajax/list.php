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
$RUNTIME_NOSETUPFS = TRUE;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkLoggedIn ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  define ('PAGE_SIZE', 100);
  $p_offset = OC_Shorty_Type::req_argument ( 'page',   OC_Shorty_Type::INTEGER, FALSE) * PAGE_SIZE;
  $p_sort   = OC_Shorty_Type::req_argument ( 'sort',   OC_Shorty_Type::SORTING, FALSE);
  $p_title  = OC_Shorty_Type::req_argument ( 'title' , OC_Shorty_Type::STRING,  FALSE);
  $p_target = OC_Shorty_Type::req_argument ( 'target', OC_Shorty_Type::STRING,  FALSE);
  $param = array
  (
    ':user'   => OC_User::getUser ( ),
    ':sort'   => $p_sort ? $p_sort : 'created',
    ':offset' => $p_offset,
    ':limit'  => PAGE_SIZE,
    ':target' => sprintf('%%%s%%',$p_target),
    ':title'  => sprintf('%%%s%%',$p_title),
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_LIST );
  $result = $query->execute($param);
  $reply = $result->fetchAll();
  OC_JSON::success ( array ( 'data'  => $reply,
                             'count' => sizeof($reply),
                             'note'  => OC_Shorty_L10n::t('Number of entries: %s', count($reply)) ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
