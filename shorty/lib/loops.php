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

namespace OCA\Shorty;

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
	 * @function deleteUser
	 * @brief Deletes all Shortys and preferences of a certain user
	 * @param array $parameters: Array of parameters from postDeleteUser-Hook
	 * @return bool
	 * @access public
	 * @author Christian Reiner
	 */
	public static function deleteUser ( $parameters )
	{
		\OCP\Util::writeLog ( 'shorty',sprintf("Wiping all Shortys belonging to user '%s'",$parameters['uid']), \OCP\Util::INFO );
		$result = TRUE;
		$param  = array ( ':user' => $parameters['uid'] );
		// wipe preferences
		$query = \OCP\DB::prepare ( Query::WIPE_PREFERENCES );
		if ( FALSE===$query->execute($param) )
			$result = FALSE;
		// wipe shortys
		$query = \OCP\DB::prepare ( Query::WIPE_SHORTYS );
		if ( FALSE===$query->execute($param) )
			$result = FALSE;
		// allow further cleanups via registered hooks
		\OCP\Util::emitHook( '\OCA\Shorty', 'post_deleteUser', array('user'=>$param['user']) );
		// report completion success
		return $result;
	}

	/**
	 * @function registerClicks
	 * @brief Hook offering information about each click relayed by this app
	 * @param $shorty
	 * @param $request
	 * @param $result
	 * @access public
	 * @author Christian Reiner
	 */
	public static function registerClick($shorty, $request, $result)
	{
		\OCP\Util::writeLog('shorty', sprintf("Registering click to shorty '%s'", $shorty['id']), \OCP\Util::DEBUG);
		// add result to details describing this request (click), important for emitting the event further down
		$request['result'] = $result;
		// save click in the database
		$param = array(
			'id' => $shorty['id'],
			'time' => $request['time'],
		);
		$query = \OCP\DB::prepare(Query::URL_CLICK);
		$query->execute($param);

		// allow further processing IF hooks are registered
		\OCP\Util::emitHook('\OCA\Shorty', 'registerClick', array('shorty' => $shorty, 'request' => $request));
	} // function registerClick

	/**
	 * @function registerQueries
	 * @brief Registers queries to be offered as expected by the Shorty app
	 * @param $parameters (array) parameters from emitted signal
	 * @return bool
	 */
	public static function registerQueries($parameters)
	{
		\OCP\Util::writeLog('shorty', 'Registering additional queries to be offered', \OCP\Util::DEBUG);
		if (!is_array($parameters)) {
			return FALSE;
		}
		if (array_key_exists('list', $parameters) && is_array($parameters['list'])) {
			$parameters['list'][] = array(
				'id' => 'shorty-list',
				'query' => Query::QUERY_SHORTY_LIST,
				'param' => array(':sort'),
			);
			$parameters['list'][] = array(
				'id' => 'shorty-single',
				'query' => Query::QUERY_SHORTY_SINGLE,
				'param' => array(':id'),
			);
		}
		return TRUE;
	} // function registerQueries

	/**
	 * @function registerDocuments
	 * @brief Registers help documents bundled with a plugin
	 * @return bool
	 */
	public function registerDocuments($container)
	{
		\OCP\Util::writeLog('shorty_tracking', 'Registering documents bundled in main Shorty app', \OCP\Util::DEBUG);
		if (is_array($container) && isset($container['payload']) && is_array($container['payload'])) {
			$container['payload'][] = new BookUserGuide;
			$container['payload'][] = new BookAdminGuide;
		}
	} // function registerDocuments

} // class Loops
