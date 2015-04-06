<?php
/**
* @package shorty-tracking an ownCloud url shortener plugin addition
* @category internet
* @author Christian Reiner
* @copyright 2012-2015 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty+Tracking?content=152473
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

namespace OCA\Shorty\Tracking;
use OCA\Shorty\Exception;

\OC::$CLASSPATH['OCA\Shorty\Exception']                          = 'shorty/lib/exception.php';
\OC::$CLASSPATH['OCA\Shorty\L10n']                               = 'shorty/lib/l10n.php';
\OC::$CLASSPATH['OCA\Shorty\Tools']                              = 'shorty/lib/tools.php';
\OC::$CLASSPATH['OCA\Shorty\Type']                               = 'shorty/lib/type.php';
\OC::$CLASSPATH['OCA\Shorty\Query']                              = 'shorty/lib/query.php';
\OC::$CLASSPATH['OCA\Shorty\Help']                               = 'shorty/lib/book.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\L10n']                      = 'shorty_tracking/lib/l10n.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Query']                     = 'shorty_tracking/lib/query.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\BookUserGuide']             = 'shorty_tracking/lib/book.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Hooks']                     = 'shorty_tracking/plugin/requests.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\AppDetails']           = 'shorty_tracking/plugin/loops/app_details.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\AppIncludes']          = 'shorty_tracking/plugin/loops/app_includes.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\QuerySingleList']      = 'shorty_tracking/plugin/loops/query_single_list.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\QuerySingleUsage']     = 'shorty_tracking/plugin/loops/query_single_usage.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\QueryTotalList']       = 'shorty_tracking/plugin/loops/query_total_list.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\QueryTotalUsage']      = 'shorty_tracking/plugin/loops/query_total_usage.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\ShortyActionTracking'] = 'shorty_tracking/plugin/loops/shorty_action_tracking.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\EventShortyRelay']     = 'shorty_tracking/plugin/loops/event_shorty_relay.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\EventShortyOrphans']   = 'shorty_tracking/plugin/loops/event_shorty_orphans.php';
\OC::$CLASSPATH['OCA\Shorty\Tracking\Loop\EventUserDelete']      = 'shorty_tracking/plugin/loops/event_user_delete.php';

try
{
	// minimum requirement currently is as specified below:
	$SHORTY_VERSION_MIN = '0.5.0';
	// only plug into the mother app 'Shorty' if that one is installed, activated and has the minimum required version:
	if ( \OC_Installer::isInstalled('shorty') )
	{
		if ( \OCP\App::isEnabled('shorty') )
		{
			// check Shorty version: installed version required
			$insV = explode ( '.', \OCP\App::getAppVersion('shorty') );
			$reqV = explode ( '.', $SHORTY_VERSION_MIN );
			if (  (sizeof($reqV)==sizeof($insV))
				&&(		  ($reqV[0]<$insV[0])
					||	( ($reqV[0]==$insV[0])&&($reqV[1]<$insV[1]) )
					||	( ($reqV[0]==$insV[0])&&($reqV[1]==$insV[1])&&($reqV[2]<=$insV[2]) ) ) ) {
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'eventShortyDelete',       'OCA\Shorty\Tracking\Loop\EventShortyDelete',    'process');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'eventShortyRelay',        'OCA\Shorty\Tracking\Loop\EventShortyRelay',     'process');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestShortyActions',    'OCA\Shorty\Tracking\Loop\ShortyActionTracking', 'register');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppIncludes',      'OCA\Shorty\Tracking\Loop\AppIncludes',          'register');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppDetails',       'OCA\Shorty\Tracking\Loop\AppDetails',           'register');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppQueries',       'OCA\Shorty\Tracking\Loop\QuerySingleList',      'register');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppQueries',       'OCA\Shorty\Tracking\Loop\QuerySingleUsage',     'register');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppQueries',       'OCA\Shorty\Tracking\Loop\QueryTotalList',       'register');
				\OCP\Util::connectHook ( 'OCA\Shorty\Hooks', 'requestAppQueries',       'OCA\Shorty\Tracking\Loop\QueryTotalUsage',      'register');
			}
			else throw new Exception ( "App 'Shorty Tracking' requires app 'Shorty' in version >= %s.%s.%s !", $reqV );
		}
		else throw new Exception ( "App 'Shorty Tracking' requires base app 'Shorty' to be activated !" );
	}
	else throw new Exception ( "App 'Shorty Tracking' requires base app 'Shorty' to be installed !" );
}
catch ( Exception $e )
{
	\OCP\Util::writeLog ( 'shorty_tracking', "Disabled because runtime requirement not met: ".$e->getMessage(), \OCP\Util::WARN );
	return;
}
