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
 * @file status.php
 * This is the plugins central position
 * All requests to the plugin are handled by this file.
 * Exceptions: system settings and user preferences dialogs
 * @access public
 * @author Christian Reiner
 */
require_once ( '../../lib/base.php' );

OC_Util::checkAppEnabled ( 'shorty' );

OC_App::setActiveNavigationEntry ( 'shorty_index' );

OC_Util::addScript ( 'shorty',  'debug' );
OC_Util::addScript ( 'shorty',  'shorty' );
OC_Util::addScript ( 'shorty',  'init' );
OC_Util::addScript ( 'shorty/3rdparty','jquery.tinysort.min' );

OC_Util::addStyle  ( 'shorty',  'shorty' );

// we assume there is only one single argument given: the http status code
// for all other calls: we handle that below, anything not recognized is assumed to be 400 (Bad Request).
$status = $_SERVER['QUERY_STRING'];

if (   is_numeric($status)
    && array_key_exists($status,OC_Shorty_Type::$HTTPCODE) )
{
  $status = intval($status);
  $expl = OC_Shorty_Type::$HTTPCODE[$status];
}
else
{
  $status = 400;
  $expl = OC_Shorty_Type::$HTTPCODE[400]; // "Bad Request"
} // switch

header ( sprintf("HTTP/1.0 %s %s",$status,$expl) );
$tmpl = new OC_Template("shorty", "tmpl_http_status", "guest");
$tmpl->assign("explanation", OC_Shorty_L10n::t($expl));
$tmpl->printPage();
exit;
