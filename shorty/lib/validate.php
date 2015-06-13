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
 * @file lib/validate.php
 * Shorty handling
 * @author Christian Reiner
 */

namespace OCA\Shorty;

/**
 * @class Validate
 * @brief Static class offering routines and constants used to handle Shorty management
 * @access public
 * @author Christian Reiner
 */
class Validate
{
	public static function userExists($user)
	{
		if ( ! \OCP\User::userExists($user)) {
			throw new Exception("User account '%s' does not exist!", ['user'=>$user]);
		}
		return true;
	}

	public static function shortyExists($id)
	{
		if ( ! is_string($id) || empty($id)) {
			throw new Exception("Invalid Shorty id '%s'!", ['id'=>$id]);
		}
		$param = ['id' => $id];
		$query = \OCP\DB::prepare ( Query::URL_BY_ID );
		if ( ! $query->execute($param)->FetchRow()) {
			throw new Exception("Shorty with id '%s' does not exist!", ['id'=>$id]);
		}
		return true;
	}

	public static function shortyIsOwnedByUser($id, $user)
	{
		$param = [
			'user' => $user,
			'id'   => $id,
		];
		$query = \OCP\DB::prepare ( Query::URL_VERIFY );
		if ( ! $query->execute($param)->FetchRow()) {
			throw new Exception("Shorty with id '%s' is not owned by user account '%s'!", $param);
		}
		return true;
	}

	public static function argsSatisfyRequirements($args, $required)
	{
		// some formal structure validation
		if ( ! is_array($args)) {
			throw new Exception("Invalid argument structure to handle Shorty");
		}
		foreach ($args as $key=>$val) {
			if ( ! is_string($key)) {
				throw new Exception("Invalid key type '%s' for argument", [gettype($key)]);
			}
			if ( ! is_scalar($val) && ! is_null($val)) {
				throw new Exception("Invalid value type '%s' for argument '%s'", [gettype($val), $key]);
			}
		}
		// check for required arguments
		if ( ! is_array($required)) {
			throw new Exception("Invalid property list to handle Shorty");
		}
		$missingArgs = array_diff($required, array_keys($args));
		if ( ! empty($missingArgs)) {
			throw new Exception("missing arguments to handle Shorty: %s", implode(', ', $missingArgs));
		}
	}
}
