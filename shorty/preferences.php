<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2014 Christian Reiner <foss@christian-reiner.info>
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
 * @file settings.php
 * This plugins user preferences dialog
 * The dialog will be included in the general framework of the user preferences page
 * @access public
 * @author Christian Reiner
 */

// Session checks
OCP\User::checkLoggedIn  ( );
OCP\App::checkAppEnabled ( 'shorty' );

$RUNTIME_NOSETUPFS = true;

OCP\Util::addStyle  ( '3rdparty', 'chosen/chosen' );
OCP\Util::addStyle  ( 'shorty',   'shorty' );
OCP\Util::addStyle  ( 'shorty',   'preferences' );

OCP\Util::addScript ( '3rdparty', 'chosen/chosen.jquery.min' );
OCP\Util::addScript ( 'shorty',   'shorty' );
OCP\Util::addScript ( 'shorty',   'util' );
OCP\Util::addScript ( 'shorty',   'preferences' );
if ( OCP\Util::DEBUG==OCP\Config::getAppValue( "loglevel", OCP\Util::WARN ) )
	OCP\Util::addScript ( 'shorty',  'debug' );


// fetch template
$tmpl = new OCP\Template ( 'shorty', 'tmpl_preferences' );
// inflate template
$backend_types = OC_Shorty_Type::$BACKENDS;
// kick out static option again if no global backend base has been specified in the system settings
$backend_static_base = OCP\Config::getAppValue('shorty','backend-static-base','');
if (   empty($backend_static_base)
	|| !parse_url($backend_static_base,PHP_URL_SCHEME)
	|| !parse_url($backend_static_base,PHP_URL_HOST) )
	unset($backend_types['static']);
// feed template engine
$tmpl->assign ( 'backend-types',       $backend_types );
$tmpl->assign ( 'backend-type',        OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-type',
                                                                OCP\Config::getAppValue('shorty','backend-default','none')) );
$tmpl->assign ( 'backend-static-base', $backend_static_base );
$tmpl->assign ( 'backend-bitly-user',  OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-bitly-user','') );
$tmpl->assign ( 'backend-bitly-key',   OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-bitly-key','') );
$tmpl->assign ( 'backend-google-key',  OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-google-key','') );
$tmpl->assign ( 'backend-tinycc-user', OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-tinycc-user','') );
$tmpl->assign ( 'backend-tinycc-key',  OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-tinycc-key','') );
$tmpl->assign ( 'backend-ssl-verify',  OCP\Config::getUserValue(OCP\User::getUser(),'shorty','backend-ssl-verify','0') );
$tmpl->assign ( 'default-status',      OCP\Config::getUserValue(OCP\User::getUser(),'shorty','default-status','private') );
$tmpl->assign ( 'sms-control',         OCP\Config::getUserValue(OCP\User::getUser(),'shorty','sms-control','disabled') );
$tmpl->assign ( 'verbosity-control',   OCP\Config::getUserValue(OCP\User::getUser(),'shorty','verbosity-control','info') );
$tmpl->assign ( 'verbosity-timeout',   OCP\Config::getUserValue(OCP\User::getUser(),'shorty','verbosity-timeout','0') );
// render template
$tmpl->printPage ( );

?>
