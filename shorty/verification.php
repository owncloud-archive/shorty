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

/**
 * function createPayload
 * @brief prepares and delivers the dialogs template
 * @access public
 * @author Christian Reiner
 */
function createPayload()
{
	try 
	{
		// fetch template
		$tmpl = new OCP\Template ( 'shorty', 'tmpl_wdg_verify', '_' ); // the undefined view '_' suppresses the typical OC framework
		// render template
		$tmpl->printPage();
	} 
	catch ( Exception $e ) 
	{
		p ( sprintf('Error %s: %s',$e->getCode(),$e->getMessage()) );
	}
}

// session checks
OCP\User::checkLoggedIn  ( );
OCP\App::checkAppEnabled ( 'shorty' );

$RUNTIME_NOSETUPFS = true;

OCP\Util::addStyle  ( 'shorty', 'shorty' );
// TODO: remove OC-4.0-compatibility:
if (OC_Shorty_Tools::versionCompare('<','4.80')) // OC-4.0
	OCP\Util::addStyle ( 'shorty', 'shorty-oc40' );
// TODO: remove OC-4.5-compatibility:
if (OC_Shorty_Tools::versionCompare('<','4.91')) // OC-4.5
	OCP\Util::addStyle ( 'shorty', 'shorty-oc45' );
OCP\Util::addStyle  ( 'shorty', 'verification' );

OCP\Util::addScript ( 'shorty', 'shorty' );
if ( OC_Log::DEBUG==OC_Config::getValue( "loglevel", OC_Log::WARN ) )
	OCP\Util::addScript ( 'shorty',  'debug' );
OCP\Util::addScript ( 'shorty', 'util' );
OCP\Util::addScript ( 'shorty', 'verification' );

// manipulate the config entry setting the Content-Security-Policy header sent by the template engine
// we modify that value to grant the ajax request required to verify the base url for the static backend
// $csp_policy = OC_Config::getValue('custom_csp_policy', FALSE); // load and get global OC config into cache
// does the configuration define a policy (does not return the default FALSE)?

// prepare and read OCs global config cache
OC_config::getKeys();

if ( ! class_exists('Reflection') )
{
	// use reflection api to make a temporary manipulation
	// make cached config value accessible for this request so the csp value can be manpipulated
	$reflection = new ReflectionClass('OC_Config');
	$property = $reflection->getProperty('cache');
	$property->setAccessible(true);
	$config = $property->getValue();

	if ( array_key_exists('custom_csp_policy',$config) )
	{
		// keep "old" config values safe
		$change = $config;
		// temporarily manipulate existing csp policy
		$change['custom_csp_policy'] = preg_replace ( "/script-src [^;]*\\w?;/", 'script-src * ;', $config['custom_csp_policy'] );
		// replace config with changed value
		$property->setValue ( $change );
		// do what has to be done
		createPayload();
		// reset config to old values
		$property->setValue ( $config );
	}
	else
	{
		// keep "old" config values safe
		$change = $config;
		// temporarily inject custome csp policy
		$change['custom_csp_policy'] = "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:";
		// replace config with changed value
		$property->setValue ( $change );
		// do what has to be done
		createPayload();
		// reset config to old values
		$property->setValue ( $config );
	}
}
else
{
	// no reflection api installed, we have to make a "normal" change to the configuration ...
	// ... since the config class automatically saves all changes we have to reset that change at the end
	if ( in_array('custom_csp_policy',OC_config::getKeys()) )
		$csp_policy = OC_config::getValue('custom_csp_policy');
	else
		$csp_policy = NULL;

	switch ( $csp_policy )
	{
		case NULL:
			// set custom value
			OC_Config::setValue ( 'custom_csp_policy', "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:" );
			// do what has to be done
			createPayload();
			// reset to missing value
			OC_Config::deleteKey ( 'custom_csp_policy' );
			break;
		case '':
			// set custom value
			OC_Config::setValue ( 'custom_csp_policy', "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:" );
			// do what has to be done
			createPayload();
			// reset to empty value
			OC_Config::setValue ( 'custom_csp_policy', '' );
			break;
		default:
			// manipulate existing value
			OC_Config::setValue ( 'custom_csp_policy', preg_replace("/script-src [^;]*\\w?;/", 'script-src * ;', $csp_policy) );
			// do what has to be done
			createPayload();
			// reset to previous value
			OC_Config::setValue ( 'custom_csp_policy', $csp_policy );
	} // switch
}

?>
