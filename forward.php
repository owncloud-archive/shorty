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

require_once ( '../../lib/base.php' );

// Note: we do NOT check if we are a user
// access to short urls is currently NOT protected by authentication
OC_Util::checkAppEnabled ( 'shorty' );

try
{
  $p_key = OC_Shorty_Type::validate ( $_SERVER['QUERY_STRING'], OC_Shorty_Type::KEY );
  $param = array
  (
    'key' => OC_Shorty_Tools::db_escape ( $p_key ),
    'now' => OC_Shorty_Tools::db_timestamp ( ),
  );
  $query  = OC_DB::prepare ( OC_Shorty_Query::URL_FORWARD );
  $result = $query->execute($param)->FetchOne();
  if ( FALSE===$result )
    throw new OC_Shorty_Exception ( "HTTP/1.0 404 Not Found", $param );
  // register click in entry
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_CLICK );
  $query->execute ( $param );
} catch ( OC_Wiki_Exception $e ) { header($e->getMessage()); }

header ( sprintf('Location: %s'), trim($result['url']) );
?>
