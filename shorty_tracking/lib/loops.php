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
 * @file lib/loops.php
 * @brief Static class providing routines to populate hooks with loops
 * @author Christian Reiner
 */

namespace OCA\Shorty\Tracking;

/**
 * @class Loops
 * @brief Static 'namespace' class for api hook population
 * ownCloud propagates to use static classes as namespaces instead of OOP.
 * This 'namespace' defines routines to populate hooks called by other parts of ownCloud
 * @access public
 * @author Christian Reiner
 */
class Loops
{
	/**
	 * @function registerDocuments
	 * @brief Registers help documents bundled with a plugin
	 * @return bool
	 */
	public function registerDocuments($container)
	{
		\OCP\Util::writeLog('shorty_tracking', 'Registering documents bundled in main Shorty app', \OCP\Util::DEBUG);
		if (is_array($container) && isset($container['payload']) && is_array($container['payload'])) {
			$container['payload'][] = new \OCA\Shorty\Tracking\BookUserGuide;
		}
	} // function registerDocuments

} // class Loops
