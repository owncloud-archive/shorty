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
 * @file lib/shorty.php
 * Shorty handling
 * @author Christian Reiner
 */

namespace OCA\Shorty;

/**
 * @class Shorty
 * @brief Static class offering routines and constants used to handle Shorty management
 * @access public
 * @author Christian Reiner
 */
class Shorty
{
	public static function add($param) {
		$param['user'] = \OCP\User::getUser();

		Validate::argsSatisfyRequirements($param, ['user', 'id', 'status', 'title', 'favicon', 'source', 'target', 'notes', 'until']);
		Validate::userExists($param['user']);

		$query = \OCP\DB::prepare ( Query::URL_INSERT );
		$query->execute($param);
	}

	public static function click($param)
	{
		$param['user'] = \OCP\User::getUser();

		Validate::argsSatisfyRequirements($param, ['user', 'address', 'host', 'time']);
		Validate::shortyExists($param['id']);

		$query = \OCP\DB::prepare ( Query::URL_INSERT );
		$query->execute($param);
	}

	public static function delete($param)
	{
		$param['user'] = \OCP\User::getUser();

		Validate::argsSatisfyRequirements($param, ['id']);
		Validate::shortyExists($param['id']);
		Validate::shortyIsOwnedByUser($param['id'], $param['user']);

		$query = \OCP\DB::prepare ( Query::URL_DELETE );
		$query->execute($param);
	}

	public static function listing($param)
	{
		$param['user'] = \OCP\User::getUser();

		Validate::argsSatisfyRequirements($param, ['user', 'sort']);
		Validate::userExists($param['user']);

		$query = \OCP\DB::prepare ( Query::URL_LIST );
		$result = $query->execute($param);
		$reply = $result->fetchAll();
		foreach (array_keys($reply) as $key) {
			if (isset($reply[$key]['id'])) {
				// enhance all entries with the relay url
				$reply[$key]['relay']=Tools::relayUrl ( $reply[$key]['id'] );
				// make sure there is _any_ favicon contained, otherwise layout in MS-IE browser is broken...
				if (empty($reply[$key]['favicon'])) {
					$reply[$key]['favicon'] = \OCP\Util::imagePath('shorty', 'blank.png');
				} else {
					$reply[$key]['favicon'] = Tools::proxifyReference('favicon', $reply[$key]['id'], false);
				}
			}
		} // foreach
		return $reply;
	}

	public static function status($param)
	{
		$param['user'] = \OCP\User::getUser();

		Validate::argsSatisfyRequirements($param, ['user', 'id', 'status']);

		$query = \OCP\DB::prepare ( Query::URL_STATUS );
		$query->execute($param);
	}

	public static function update($param)
	{
		$param['user'] = \OCP\User::getUser();

		Validate::argsSatisfyRequirements($param, ['user', 'id', 'status', 'title', 'favicon', 'target', 'notes', 'until']);
		Validate::userExists($param['user']);
		Validate::shortyIsOwnedByUser($param['id'], $param['user']);

		$query = \OCP\DB::prepare ( Query::URL_UPDATE );
		$query->execute($param);
	}
}
