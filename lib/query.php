<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011 Christian Reiner <foss@christian-reiner.info>
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
 * @brief Catalog of sql queries
 */
class OC_Shorty_Query
{
  const URL_INSERT            = "INSERT INTO *PREFIX*shorty (key,source,target,user,until,created,notes) VALUES (:key,:source,:target,:user,:until,:created,:notes)";
  const URL_DELETE            = "DELETE FROM *PREFIX*shorty WHERE user=:user AND key=:key";
  const URL_UPDATE            = "UPDATE *PREFIX*shorty SET notes=:notes WHERE WHERE user=:user AND key=:key";
  const URL_CLICK             = "UPDATE *PREFIX*shorty SET accessed=:now, clicks=(clicks+1) WHERE key=:key";
  const URL_FORWARD           = "SELECT target FROM *PREFIX*shorty WHERE key=:key AND until>:now LIMIT 1";
  const URL_VERIFY            = "SELECT key,source,target,clicks,created,accessed,until,notes FROM *PREFIX*shorty WHERE user=:user AND key=:key LIMIT 1";
  const URL_LIST              = "SELECT key,source,target,clicks,created,accessed,until,notes FROM *PREFIX*shorty WHERE user=:user AND target LIKE :target AND notes LIKE :notes ORDER BY :sort LIMIT :limit OFFSET :offset";
//  const URL_LIST              = "SELECT key,source,target,clicks,created,accessed,until,notes FROM *PREFIX*shorty WHERE user=:user ORDER BY :sort";
//  const URL_LIST              = "SELECT key,source,target,clicks,created,accessed,until,notes FROM *PREFIX*shorty";
  const KEY_CHECK             = "SELECT COUNT(*) AS count FROM *PREFIX*shorty WHERE key=:key";
} // class OC_Wiki_Query
?>
