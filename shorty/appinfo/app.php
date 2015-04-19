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

\OCP\App::registerAdmin      ( 'shorty', 'settings' );
\OCP\App::addNavigationEntry ( [
	'id' => 'shorty_index',
	'order' => 71,
	'href' => \OCP\Util::linkTo   ( 'shorty', 'index.php' ),
	'icon' => \OCP\Util::imagePath( 'shorty', 'shorty-light.svg' ),
	'name' => 'Shorty'
] );

\OC::$CLASSPATH['OCA\Shorty\Backend']                  = 'shorty/lib/backend.php';
\OC::$CLASSPATH['OCA\Shorty\Exception']                = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\HttpException']            = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\L10n']                     = 'shorty/lib/l10n.php';
\OC::$CLASSPATH['OCA\Shorty\Meta']                     = 'shorty/lib/meta.php';
\OC::$CLASSPATH['OCA\Shorty\Query']                    = 'shorty/lib/query.php';
\OC::$CLASSPATH['OCA\Shorty\Tools']                    = 'shorty/lib/tools.php';
\OC::$CLASSPATH['OCA\Shorty\Type']                     = 'shorty/lib/type.php';
\OC::$CLASSPATH['OCA\Shorty\Hooks']                    = 'shorty/plugin/requests.php';

\OC::$CLASSPATH['OCA\Shorty\Plugin\Atom']              = 'shorty/plugin/atoms/atom.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\Event']             = 'shorty/plugin/loops/event.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\Loop']              = 'shorty/plugin/loops/loop.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopAppDetails']    = 'shorty/plugin/loops/loop_app_details.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopAppIncludes']   = 'shorty/plugin/loops/loop_app_includes.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopAppQuery']      = 'shorty/plugin/loops/loop_app_query.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopShortyAction']  = 'shorty/plugin/loops/loop_shorty_action.php';
\OC::$CLASSPATH['OCA\Shorty\Plugin\LoopShortyEvent']   = 'shorty/plugin/loops/loop_shorty_event.php';

\OC::$CLASSPATH['OCA\Shorty\Atom\AtomRequest']         = 'shorty/plugin/atoms/atom_request.php';
\OC::$CLASSPATH['OCA\Shorty\Atom\AtomShorty']          = 'shorty/plugin/atoms/atom_shorty.php';

\OC::$CLASSPATH['OCA\Shorty\Loop\EventUserDelete']     = 'shorty/plugin/loops/event_user_delete.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\QueryShortyList']     = 'shorty/plugin/loops/query_shorty_list.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\QueryShortySingle']   = 'shorty/plugin/loops/query_shorty_single.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\ShortyActionShow']    = 'shorty/plugin/loops/shorty_action_show.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\ShortyActionEdit']    = 'shorty/plugin/loops/shorty_action_edit.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\ShortyActionDelete']  = 'shorty/plugin/loops/shorty_action_delete.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\ShortyActionShare']   = 'shorty/plugin/loops/shorty_action_share.php';
\OC::$CLASSPATH['OCA\Shorty\Loop\ShortyActionOpen']    = 'shorty/plugin/loops/shorty_action_open.php';

\OC::$CLASSPATH['OCA\Shorty\Hook\Events']              = 'shorty/plugin/hooks/events.php';
\OC::$CLASSPATH['OCA\Shorty\Hook\Requests']            = 'shorty/plugin/hooks/requests.php';

\OCP\Util::connectHook ( 'OC_User',          'post_deleteUser',         'OCA\Shorty\Loop\EventUserDelete',    'process');
\OCP\Util::connectHook ( 'OC\User',          'postDelete',              'OCA\Shorty\Loop\EventUserDelete',    'process');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppQueries',       'OCA\Shorty\Loop\QueryShortyList',    'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppQueries',       'OCA\Shorty\Loop\QueryShortySingle',  'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\Loop\ShortyActionShow',   'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\Loop\ShortyActionEdit',   'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\Loop\ShortyActionDelete', 'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\Loop\ShortyActionShare',  'register');
\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\Loop\ShortyActionOpen',   'register');
