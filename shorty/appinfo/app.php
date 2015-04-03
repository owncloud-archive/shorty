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

\OC::$CLASSPATH['OCA\Shorty\Backend']                  = 'shorty/lib/backend.php';
\OC::$CLASSPATH['OCA\Shorty\Exception']                = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\HttpException']            = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\L10n']                     = 'shorty/lib/l10n.php';
\OC::$CLASSPATH['OCA\Shorty\Meta']                     = 'shorty/lib/meta.php';
\OC::$CLASSPATH['OCA\Shorty\Query']                    = 'shorty/lib/query.php';
\OC::$CLASSPATH['OCA\Shorty\Tools']                    = 'shorty/lib/tools.php';
\OC::$CLASSPATH['OCA\Shorty\Type']                     = 'shorty/lib/type.php';
\OC::$CLASSPATH['OCA\Shorty\Hooks']                    = 'shorty/plugin/hooks.php';
\OC::$CLASSPATH['OCA\Shorty\Loops']                    = 'shorty/plugin/loops.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\Loop']              = 'shorty/plugin/loops/loop.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopShortyAction']  = 'shorty/plugin/loops/loop_shorty_action.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopShortyEvent']   = 'shorty/plugin/loops/loop_shorty_event.php';
\OC::$CLASSPATH['OCA\Shorty\ShortyActionShow']         = 'shorty/plugin/loops/shorty_action_show.php';
\OC::$CLASSPATH['OCA\Shorty\ShortyActionEdit']         = 'shorty/plugin/loops/shorty_action_edit.php';
\OC::$CLASSPATH['OCA\Shorty\ShortyActionDelete']       = 'shorty/plugin/loops/shorty_action_delete.php';
\OC::$CLASSPATH['OCA\Shorty\ShortyActionShare']        = 'shorty/plugin/loops/shorty_action_share.php';
\OC::$CLASSPATH['OCA\Shorty\ShortyActionOpen']         = 'shorty/plugin/loops/shorty_action_open.php';

\OCP\App::registerAdmin      ( 'shorty', 'settings' );
\OCP\App::addNavigationEntry ( [
	'id' => 'shorty_index',
	'order' => 71,
	'href' => \OCP\Util::linkTo   ( 'shorty', 'index.php' ),
	'icon' => \OCP\Util::imagePath( 'shorty', 'shorty-light.svg' ),
	'name' => 'Shorty'
] );

\OCP\Util::connectHook ( 'OCP\User',         'post_deleteUser',         'OCA\Shorty\Loops',              'deleteUser');
\OCP\Util::connectHook ( 'OCA\Shorty',       'registerQueries',         'OCA\Shorty\Loops',              'registerQueries');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\ShortyActionShow',   'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\ShortyActionEdit',   'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\ShortyActionDelete', 'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\ShortyActionShare',  'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\ShortyActionOpen',   'register');
