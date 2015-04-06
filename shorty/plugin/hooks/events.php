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
 * @file plugin/hooks/events.php
 * @brief Static class calling routines registered by plugins to populate hooks
 * @author Christian Reiner
 */

namespace OCA\Shorty\Hook;
use OCA\Shorty\Query;

/**
 * @class Events
 * @brief Static class for api hook population
 * @access public
 * @author Christian Reiner
 */
class Events
{
	/**
	 * @function raiseEventShortyRelay
	 * @brief Hook offering information about each click relayed by this app
	 * @param $shorty
	 * @param $request
	 * @param $result
	 * @access public
	 * @author Christian Reiner
	 */
	public static function raiseEventShortyRelay($shorty, $request, $result)
	{
		\OCP\Util::writeLog('shorty', sprintf("Registering click to shorty '%s'", $shorty->getId()), \OCP\Util::DEBUG);
		// register click event in database, required for a "last accessed"
		$param = array(
			'id'   => $shorty->getId(),
			'time' => $request->getTime(),
		);
		$query = \OCP\DB::prepare(Query::URL_CLICK);
		$query->execute($param);

		// allow further processing IF hooks are registered
		\OCP\Util::emitHook('OCA\Shorty\Hooks', 'eventShortyRelay', [$shorty, $request, $result]);
	} // function raiseShortyEventRequest

} // class Events
