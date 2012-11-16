<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty
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
 * @file appinfo/update.php
 * @brief Update database structure and content upon version change
 * @author Christian Reiner
 */

$installedVersion=OCP\Config::getAppValue('shorty', 'installed_version');

if (version_compare($installedVersion, '0.3.11', '<')) {
	/* version 0.3.11 fixes the visualization of the 'accessed' attribute: the timestamp the Shorty was last accessed
	 * unfortunately the different database engines handle such values with huge differences
	 * to keep things simple we convert the column to a numerical type (BIGINT(20))
	 * exception: sqlite engines, the columns are already of numerical type
	 */

	switch (strtolower(OCP\Config::getSystemValue('dbtype','sqlite'))) {
		case 'sqlite':
		case 'sqlite3':
			OCP\Util::writeLog( 'shorty', "Updating database: altering type of column 'accessed' not required", OC_Log::INFO );
			break;

		default:
			// alter type of column 'accessed' to numerical, this offers less differences between different database engines
			OCP\Util::writeLog( 'shorty', "Updating database: altering type of column 'accessed' to numerical", OC_Log::INFO );
			$query = OCP\DB::prepare ( "ALTER TABLE `*PREFIX*shorty` MODIFY COLUMN accessed BIGINT(20) UNSIGNED NOT NULL");
			$result = $stmt->execute ( array() );

			break;
	} // switch
}
