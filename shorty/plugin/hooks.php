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
		\OCP\Util::writeLog ( 'shorty', 'Requesting documents registered by other apps', \OCP\Util::DEBUG );
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
				\OCP\Util::writeLog ( 'shorty', sprintf("'Ignoring registered object of type '%s'", get_class($loop)), \OCP\Util::WARNING );
			}
		}
		return $loops;
	} // function requestLoop

	/**
	 * @function requestActions
	 * @brief Hook that requests any actions plugins may want to register
	 * @return array: Array of descriptions of actions
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestActions ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting actions to be offered for Shortys by other apps', \OCP\Util::DEBUG );
		return self::requestLoop('OCA\Shorty\Hooks', 'requestShortyActions', '\OCA\Shorty\Tracking\ShortyActionTracking');
	} // function requestActions

	/**
	 * @function requestDetails
	 * @brief Hook that requests any plugin details (id and abstract) plugins may want to register
	 * @return array: Array of details of plugins
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestDetails ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting plugin details registered by other apps', \OCP\Util::DEBUG );
		$details = [ 'list'=>array(), 'shorty'=>array() ];
		// we hand over a container by reference and expect any app registering into this hook to obey this structure:
		// ... for every action register a new element in the container
		// ... ... such element must be an array holding the entries tested below
		$container = [ 'shorty'=>&$details['shorty'] ];
		\OCP\Util::emitHook ( 'OCA\Shorty\Hooks', 'registerDetails', $container );
		// validate and evaluate what was returned in the $container
		if ( ! is_array($container))
		{
			\OCP\Util::writeLog ( 'shorty', 'Invalid reply from some app that registered into the registerDetails hook, FIX THAT APP !', \OCP\Util::WARN );
			return [];
		} // if
		foreach ( $container as $aspect )
		{
			if ( ! is_array($aspect) )
			{
				\OCP\Util::writeLog ( 'shorty', 'Invalid reply structure from an app that registered into the registerDetails hook, FIX THAT APP !', \OCP\Util::WARN );
				break;
			}
			foreach ( $aspect as $detail )
			{
				if (  ! is_array($detail)
					|| ! array_key_exists('id',       $detail) || ! is_string($detail['id'])
					|| ! array_key_exists('name',     $detail) || ! is_string($detail['name'])
					|| ! array_key_exists('abstract', $detail) || ! is_string($detail['abstract']) )
				{
					\OCP\Util::writeLog ( 'shorty', 'Invalid reply from an app that registered into the registerDetails hook, FIX THAT APP !', \OCP\Util::WARN );
					break;
				}
			} // foreach aspect
		} // foreach aspect
		return $details;
	} // function requestDetails

	/**
	 * @function requestIncludes
	 * @brief Hook that requests any includes plugins may want to register
	 * @return array: Array of descriptions of actions
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestIncludes ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting includes registered by other apps', \OCP\Util::DEBUG );
		\OCP\Util::emitHook ( 'OCA\Shorty\Hooks', 'registerIncludes', [] );
	} // function requestIncludes

	/**
	 * @function requestQueries
	 * @brief Hook that requests any queries plugins may want to offer
	 * @return array: Array of descriptions of queries
	 * @static
	 * @access public
	 * @author Christian Reiner
	 */
	public static function requestQueries ( )
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting queries to be offered from other apps', \OCP\Util::DEBUG );
		$queries = [ 'list'=>array(), 'shorty'=>array() ];
		// we hand over a container by reference and expect any app registering into this hook to obey this structure:
		// ... for every action register a new element in the container
		// ... ... such element must be an array holding the entries tested below
		$container = [ 'list'=>&$queries['list'], 'shorty'=>&$queries['shorty'] ];
		\OCP\Util::emitHook ( 'OCA\Shorty\Hooks', 'registerQueries', $container );
		// validate and evaluate what was returned in the $container
		if ( ! is_array($container))
		{
			\OCP\Util::writeLog ( 'shorty', 'Invalid reply from some app that registered into the registerQueries hook, FIX THAT APP !', \OCP\Util::WARN );
			return [];
		} // if
		foreach ( $container as $aspect )
		{
			if ( ! is_array($aspect) )
			{
				\OCP\Util::writeLog ( 'shorty', 'Invalid reply structure from an app that registered into the registerQueries hook, FIX THAT APP !', \OCP\Util::WARN );
				break;
			}
			foreach ( $aspect as $query )
			{
				if (  ! is_array($query)
					|| ! array_key_exists('id',    $query) || ! is_string($query['id'])
					|| ! array_key_exists('query', $query) || ! is_string($query['query'])
					|| ! array_key_exists('param', $query) || ! is_array($query['param']) )
				{
					\OCP\Util::writeLog ( 'shorty', 'Invalid reply from an app that registered into the registerQueries hook, FIX THAT APP !', \OCP\Util::WARN );
					break;
				}
			} // foreach query
		} // foreach aspect
		return $queries;
	} // function requestQueries

	/**
	 * @method requestDocuments
	 * @brief Registers documents bundled with plugins
	 * @return array: List of registered documents
	 * @static
	 * @access public
	 * @return array List of plugs, documents in this case
	 */
	public static function requestDocuments()
	{
		\OCP\Util::writeLog ( 'shorty', 'Requesting documents registered by other apps', \OCP\Util::DEBUG );
		$payload = array();
		// we hand over a container by reference and expect any app registering into this hook to obey this structure:
		// ... for every action register a new element in the container
		// ... ... such element must be an array holding the entries tested below
		$container = array ( 'payload'=>&$payload );
		\OCP\Util::emitHook ( 'OCA\Shorty', 'registerDocuments', $container );
		// validate result
		$documents = array();
		foreach ($payload as $document) {
			if (is_a($document, '\OCA\Shorty\Plugin\Book')) {
				\OCP\Util::writeLog ( 'shorty', sprintf('Accepting registered document with title "%s"', $document->getTitle()), \OCP\Util::DEBUG );
				$documents[] = $document;
			} else {
				\OCP\Util::writeLog ( 'shorty', sprintf('Ignoring registered document of type %s', get_class($document)), \OCP\Util::WARNING );
			}
		}
		return $documents;
	} // function requestDocuments

} // class Hooks
