<?php
/**
* @package shorty-tracking an ownCloud url shortener plugin addition
* @category internet
* @author Christian Reiner
* @copyright 2012-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information 
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty-tracking
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
 * @file lib/query.php
 * Static catalog of sql queries
 * @author Christian Reiner
 */

/**
 * @class OC_ShortyTracking_Query
 * @brief Static catalog of sql queries
 * These query templates are referenced by a OC_Shorty_Query::URL_...
 * They have to be prapared by adding an array of parameters
 * @access public
 * @author Christian Reiner
 */
class OC_ShortyTracking_Query
{
  const CLICK_RECORD            = "INSERT INTO *PREFIX*shorty_tracking (shorty,time,address,host,user,result) VALUES (:shorty,:time,:address,:host,:user,:result)";
  const CLICK_LIST              = "SELECT time,address,user,result FROM *PREFIX*shorty_tracking WHERE shorty=:shorty ORDER BY time desc LIMIT :limit";
  const CLICK_WIPE              = "DELETE FROM *PREFIX*shorty_tracking ..... all clicks where entry in *PREFIX*shorty does NOT exist any more";
} // class OC_ShortyTracking_Query
?>
