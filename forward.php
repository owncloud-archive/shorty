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

require_once ( '../../lib/base.php' );

// Note: we do NOT check if we are a user
// access to short urls is currently NOT protected by authentication
OC_Util::checkAppEnabled ( 'shorty' );

try
{
  // a safe target to forward to in case of problems: the shorty module
  $target = OC_Helper::linkTo( 'shorty', 'index.php' , false);
  // detect requested shorty key from request
  $p_key = trim ( OC_Shorty_Type::normalize($_SERVER['QUERY_STRING'],OC_Shorty_Type::KEY) ) ;
  // a key was specified, look for matching entry in database
  if ( '<shorty key>'==$p_key )
  {
    // this is a pseudo key, used for to test the setup, so forward to a positive message.
    OC_JSON::success ( array ( ) );
  }
  else if ( $p_key )
  {
    $param = array
    (
      'key' => OC_Shorty_Tools::db_escape ( $p_key ),
    );
    $query  = OC_DB::prepare ( OC_Shorty_Query::URL_FORWARD );
    $result = $query->execute($param)->FetchOne();
    if ( FALSE===$result )
      throw new OC_Shorty_Exception ( "HTTP/1.0 404 Not Found", $param );
    // and usable target ? stick with fallback otherwise
    if ( trim($result) )
      $target = trim($result);
    // register click in shorty
    $query = OC_DB::prepare ( OC_Shorty_Query::URL_CLICK );
    $query->execute ( $param );
  } // if key
} catch ( OC_Shorty_Exception $e ) { header($e->getMessage()); }

// http forwarding header
header ( sprintf('Location: %s', $target) );
?>
