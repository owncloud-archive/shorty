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
 * @file plugin/hooks.php
 * @brief Static class calling routines registered by plugins to populate hooks
 * @author Christian Reiner
 */

namespace OCA\Shorty;

/**
 * @class Hooks
 * @brief Static class for api hook population
 * @access public
 * @author Christian Reiner
 */
class Hooks
{
	/**
	 * @method requestLoop
	 * @brief Requests a loop to be registered onto a hook
	 * @return array: List of registered loops
	 * @static
	 * @access public
	 * @return array List of plugs, documents in this case
	 */
	protected static function requestLoop($hookClass, $hookMethod, $expectedLoopType)
	{
		$payload = array();
		// we hand over a container by reference and expect any app registering into this hook to obey this structure:
		// ... for every action register a new element in the container
		// ... ... such element must be an array holding the entries tested below
		$container = array ( 'payload'=>&$payload );
		\OCP\Util::emitHook ( $hookClass, $hookMethod, $container );
		// validate result
		$loops = array();
		foreach ($payload as $loop) {
			if (is_a($loop, '\OCA\Shorty\Plugin\Loop')
				&&($expectedLoopType && is_a($loop, $expectedLoopType))) {
				\OCP\Util::writeLog ( 'shorty', sprintf("Accepting registered object of type '%s'", get_class($loop)), \OCP\Util::DEBUG );
				$loops[] = $loop;
			} else {
				\OCP\Util::writeLog ( 'shorty', sprintf("'Ignoring registered object of type '%s'", get_class($loop)), \OCP\Util::INFO );
			}
		}
		usort($loops, function($A,$B){ return ($A::getLoopIndex() < $B::getLoopIndex()); } );
		\OCP\Util::writeLog ( 'shorty', sprintf("Received %s hook loops in sum when requesting hook '%s' of type '%s'", count($loops), $hookMethod, $expectedLoopType), \OCP\Util::DEBUG );
		return $loops;
	} // function requestLoop



	/**
	 * @function requestAppDetails
	 * @brief Hook that requests any plugin details (id and abstract) plugins may want to register
	 * @return array: Array of details of plugins
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestAppDetails ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting plugin details registered by other apps', \OCP\Util::DEBUG );
		return self::requestLoop('OCA\Shorty\Hooks', 'requestAppDetails', '\OCA\Shorty\Plugin\LoopAppDetails');
	} // function requestAppDetails

	/**
	 * @function requestAppIncludes
	 * @brief Hook that requests any js or css includes plugins may want to register
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestAppIncludes ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting includes registered by other apps', \OCP\Util::DEBUG );
		return self::requestLoop('OCA\Shorty\Hooks', 'requestAppIncludes', '\OCA\Shorty\Plugin\LoopAppIncludes');
	} // function requestAppIncludes

	/**
	 * @function requestAppQueries
	 * @brief Hook that requests any database queries to be registered by plugins
	 * @return array: list of queries
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestAppQueries ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting database queries to be offered by other apps', \OCP\Util::DEBUG );
		return self::requestLoop('OCA\Shorty\Hooks', 'requestAppQueries', '\OCA\Shorty\Plugin\LoopAppQuery');
	} // function requestAppQueries



	/**
	 * @function requestShortyActions
	 * @brief Hook that requests any actions plugins may want to register
	 * @return array: Array of descriptions of actions
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestShortyActions ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting actions to be offered for Shortys by other apps', \OCP\Util::DEBUG );
		return self::requestLoop('OCA\Shorty\Hooks', 'requestShortyActions', '\OCA\Shorty\Plugin\LoopShortyAction');
	} // function requestShortyActions

} // class Hooks
