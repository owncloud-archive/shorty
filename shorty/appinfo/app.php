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
 * @file appinfo/app.php
 * @brief Basic registration of plugin at ownCloud
 * @author Christian Reiner
 */

namespace OCA\Shorty;

\OC::$CLASSPATH['OCA\Shorty\Backend']       = 'shorty/lib/backend.php';
\OC::$CLASSPATH['OCA\Shorty\Exception']     = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\Hooks']         = 'shorty/lib/hooks.php';
\OC::$CLASSPATH['OCA\Shorty\HttpException'] = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\L10n']          = 'shorty/lib/l10n.php';
\OC::$CLASSPATH['OCA\Shorty\Meta']          = 'shorty/lib/meta.php';
\OC::$CLASSPATH['OCA\Shorty\Query']         = 'shorty/lib/query.php';
\OC::$CLASSPATH['OCA\Shorty\Tools']         = 'shorty/lib/tools.php';
\OC::$CLASSPATH['OCA\Shorty\Type']          = 'shorty/lib/type.php';

\OCP\App::registerAdmin      ( 'shorty', 'settings' );
\OCP\App::addNavigationEntry ( [
	'id' => 'shorty_index',
	'order' => 71,
	'href' => \OCP\Util::linkTo   ( 'shorty', 'index.php' ),
	'icon' => \OCP\Util::imagePath( 'shorty', 'shorty-light.svg' ),
	'name' => 'Shorty'
] );

\OCP\Util::connectHook ( 'OCP\User',         'post_deleteUser', 'OCA\Shorty\Hooks', 'deleteUser');
\OCP\Util::connectHook ( 'OCA\Shorty\Query', 'registerQueries', 'OCA\Shorty\Hooks', 'registerQueries');
