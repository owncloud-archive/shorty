<?php
/**
 * @package shorty an ownCloud url shortener plugin
 * @category internet
 * @author Christian Reiner
 * @copyright 2011-2015 Christian Reiner <foss@christian-reiner.info>
 * @license GNU Affero General Public license (AGPL)
 * @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
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
 * @file plugin/loops/event_user_delete.php
 * @author Christian Reiner
 */

namespace OCA\Shorty\Loop;

/**
 * @class OCA\Shorty\Loop\EventUserDelete
 * @extends \OCA\Shorty\Plugin\Event
 * @brief react on a user account getting deleted
 * @access public
 * @author Christian Reiner
 */
class EventUserDelete extends \OCA\Shorty\Plugin\Event
{
	public function process($param) {
		$user = $param['uid'];
		syslog(LOG_DEBUG,'***');
		syslog(LOG_DEBUG,json_encode($param));
		syslog(LOG_DEBUG,$user);
		syslog(LOG_DEBUG,'***');
		\OCP\Util::writeLog ( 'shorty_tracking', sprintf("Wiping all Shortys of deleted user '%s'", $user), \OCP\Util::INFO );
		// wipe shortys
		$query = \OCP\DB::prepare ( \OCA\Shorty\Query::WIPE_SHORTYS );
		$query->execute(['user'=>$user]);
		// wipe preferences
		$query = \OCP\DB::prepare ( \OCA\Shorty\Query::WIPE_PREFERENCES );
		$query->execute(['user'=>$user]);
	}
}
