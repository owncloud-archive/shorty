<?php
/**
 * @package shorty-tracking an ownCloud url shortener plugin addition
 * @category internet
 * @author Christian Reiner
 * @copyright 2012-2015 Christian Reiner <foss@christian-reiner.info>
 * @license GNU Affero General Public license (AGPL)
 * @link information http://apps.owncloud.com/content/show.php/Shorty+Tracking?content=152473
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
 * @file plugin/loops/loop_app_query.php
 * @author Christian Reiner
 */

namespace OCA\Shorty\Plugin;

/**
 * @class \OCA\Shorty\Plugin\LoopAppQuery
 * @extends \OCA\Shorty\Plugin\Loop
 * @brief Represents the list of database queries offered by an app
 * @access public
 * @author Christian Reiner
 */
class LoopAppQuery extends Loop
{
	static $QUERY_KEY       = null;
	static $QUERY_STATEMENT = null;
	static $QUERY_PARAM     = null;

	public function getQueryKey()       { return static::$QUERY_KEY;       }
	public function getQueryStatement() { return static::$QUERY_STATEMENT; }
	public function getQueryParam()     { return static::$QUERY_PARAM;     }
}
