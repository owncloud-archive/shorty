<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information 
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
 * @file settings.php
 * This plugins user preferences dialog
 * The dialog will be included in the general framework of the user preferences page
 * @access public
 * @author Christian Reiner
 */

OC_Util::addStyle  ( '3rdparty', 'chosen/chosen' );
OC_Util::addStyle  ( 'shorty',   'shorty' );
OC_Util::addStyle  ( 'shorty',   'preferences' );

OC_Util::addScript ( '3rdparty', 'chosen/chosen.jquery.min' );
OC_Util::addScript ( 'shorty',   'shorty' );
// OC_Util::addScript ( 'shorty', 'debug' );
OC_Util::addScript ( 'shorty',   'preferences' );


// fetch template
$tmpl = new OC_Template ( 'shorty', 'tmpl_preferences' );
// inflate template
$backend_types = OC_Shorty_Type::$BACKENDS;
// kick out static option again if no global backend base has been specified in the system settings
$backend_static_base = OC_Appconfig::getValue('shorty','backend-static-base','');
if (   empty($backend_static_base)
    || !parse_url($backend_static_base,PHP_URL_SCHEME)
    || !parse_url($backend_static_base,PHP_URL_HOST) )
  unset($backend_types['static']);
// feed template engine
$tmpl->assign ( 'backend-types',       $backend_types );
$tmpl->assign ( 'backend-static-base', $backend_static_base );
$tmpl->assign ( 'backend-bitly-user',  OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-bitly-user','') );
$tmpl->assign ( 'backend-bitly-key',   OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-bitly-key','') );
$tmpl->assign ( 'backend-google-key',  OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-google-key','') );
$tmpl->assign ( 'backend-tinycc-user', OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-tinycc-user','') );
$tmpl->assign ( 'backend-tinycc-key',  OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-tinycc-key','') );
$tmpl->assign ( 'backend-type',        OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-type','') );
$tmpl->assign ( 'sms-control',         OC_Preferences::getValue(OC_User::getUser(),'shorty','sms-control','disabled') );
// render template
return $tmpl->fetchPage ( );
?>
