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
 * @file verification.php
 * Verifies a specified url whether it is valid to be used as a base url for the static backend.
 * @access public
 * @author Christian Reiner
 */

// session checks
OCP\App::checkAppEnabled ( 'shorty' );

$RUNTIME_NOSETUPFS = true;

$tmpl = new OCP\Template( 'shorty', 'tmpl_wdg_verify' );

// is that target plausible AT ALL?
try {
	$target = OC_Shorty_Type::req_argument ( 'target', OC_Shorty_Type::URL, TRUE );
} catch ( OC_Shorty_Exception $e ) {
	$target = false;
}

try {
	// we simply use a generated shorty id as a js 'nonce', since its syntax suits the requirements here:
	$nonce = OC_Shorty_Tools::shorty_id();
	// we just include the template, since using the template engine would create a whole bunch of problems...
	// notably the OC wide csp policy sent as a header would prevent out js based verification to be blocked...
// 	header_remove ( 'Content-Security-Policy' );
	header (sprintf("Content-Security-Policy: ".
									"default-src 'self'; ".
									"script-src 'nonce-%1\$s'; ".
									"style-src 'nonce-%1\$s'; ".
									"frame-src 'self'; ".
									"img-src 'self'; ".
									"font-src 'self'; ".
									"media-src 'self'",
									$nonce) );
	// prevent caching of result
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: 0");
	// we simply use a generated shorty id as a js nonce, since its syntax suits the requirements here:
	$tmpl->assign ( 'nonce',  $nonce  );
	$tmpl->assign ( 'target', $target );
	// output the template
	$tmpl->printPage();
} catch ( OC_Shorty_Exception $e ) {}
