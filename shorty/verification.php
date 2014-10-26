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

// we just include the template, since using the template engine create a whole bunch of problems...
// notably the OC wide csp policy sent as a header would prevent out js based verification to be blocked...
header("Content-Security-Policy: default-src 'self'; script-src * ; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:");
include 'templates/tmpl_wdg_verify.php';

?>
