<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2013 Christian Reiner <foss@christian-reiner.info>
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
 * @file verification.php
 * Verifies a specified url whether it is valid to be used as a base url for the static backend. 
 * @access public
 * @author Christian Reiner
 */

// session checks
OCP\User::checkLoggedIn  ( );
// OCP\User::checkAdminUser ( );
OCP\App::checkAppEnabled ( 'shorty' );

$RUNTIME_NOSETUPFS = true;

// OCP\Util::addStyle  ( 'shorty', 'shorty' );
// // TODO: remove OC-4.0-compatibility:
// if (OC_Shorty_Tools::versionCompare('<','4.80')) // OC-4.0
// 	OCP\Util::addStyle ( 'shorty', 'shorty-oc40' );
// // TODO: remove OC-4.5-compatibility:
// if (OC_Shorty_Tools::versionCompare('<','4.91')) // OC-4.5
// 	OCP\Util::addStyle ( 'shorty', 'shorty-oc45' );
// OCP\Util::addStyle  ( 'shorty', 'verification' );
// 
// OCP\Util::addScript ( 'shorty', 'shorty' );
// if ( OC_Log::DEBUG==OC_Config::getValue( "loglevel", OC_Log::WARN ) )
// 	OCP\Util::addScript ( 'shorty',  'debug' );
// OCP\Util::addScript ( 'shorty', 'util' );
// OCP\Util::addScript ( 'shorty', 'verification' );

// simulate the existance of OC5's 'p' functions defined in template.php
if ( ! function_exists('p'))
{
	function p($string) {
		print(OC_Util::sanitizeHTML($string));
	}
}
if ( ! function_exists('print_unescaped'))
{
	function print_unescaped($string) {
		print($string);
	}
}

// we just include the template, since using the template engine create a whole bunch of problems...
// notably the OC wide csp policy sent as a header would prevent out js based verification to be blocked...
header("Content-Security-Policy: default-src 'self'; script-src * ; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:");
include 'templates/tmpl_wdg_verify.php';

?>
