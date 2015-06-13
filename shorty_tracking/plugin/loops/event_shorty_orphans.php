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
 * @file plugin/loops/shorty_action_clicks.php
 * Static class providing routines to populate hooks called by other parts of ownCloud
 * @author Christian Reiner
 */

namespace OCA\Shorty\Tracking\Loop;

/**
 * @class OCA\Shorty\Tracking\Loop\EventShortyOrphans
 * @extends \OCA\Shorty\Plugin\Event
 * @brief Deletes all orphaned tracking entries
 * @access public
 * @author Christian Reiner
 */
class EventShortyOrphans extends \OCA\Shorty\Plugin\Event
{
	public function process() {
		\OCP\Util::writeLog ( 'shorty_tracking', 'Wiping all orphaned tracking entries without corresponding Shorty', \OCP\Util::INFO );
		$result = TRUE;
			// wipe shorty clicks
		$query = \OCP\DB::prepare ( Query::CLICK_WIPE_ALL );
		return FALSE===$query->execute();
	}
}
