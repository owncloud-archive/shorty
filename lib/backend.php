<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
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
 * @brief Routines to retrieve info about a remote url
 */

class OC_Shorty_Backend
{
  // ===== OC_Shorty_Backend::chooseStaticBackendBase =====
  static function chooseStaticBackendBase ( )
  {
    // use the users personal preference if stored or
    // use system wide setting, if no personal preference
    // bail out otherwise
    if (  (FALSE===($base = OC_Preference::getValue(OC_User::getUser(),'shorty','backend-static-base',FALSE)))
        &&(FALSE===($base = OC_Appconfig::getValue (                   'shorty','backend-static-base-system', FALSE))) )
      throw new OC_Shorty_Exception ( "No base configured for the usage of a 'static' backend" );
    return $base;
  }, // OC_Shorty_Backend::chooseStaticBackendBase
  
  // OC_Shorty_Backend::registerUrl
  static function registerUrl ( $key )
  {
    try
    {
      // construct the $relay, the url to be called to reach THIS service (ownclouds shorty plugin)
      $relay = OC_Helper::linkTo('shorty','forward.php?'.$key);
      // call backend specific work horse
      switch ( $type=OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-type','') )
      {
        default:        return registerUrl_default ( $key, $relay );
        case 'static':  return registerUrl_static  ( $key, $relay );
        case 'google':  return registerUrl_google  ( $key, $relay );
        case 'tinyurl': return registerUrl_tinyurl ( $key, $relay );
        case 'isgd':    return registerUrl_isgd    ( $key, $relay );
        case 'bitly':   return registerUrl_bitly   ( $key, $relay );
      } // switch
    } // try
    catch (OC_Shorty_Exception $e)
    {
      throw new OC_Shorty_Exception ( "Failed to register url '%s' for '%s' backend", $url, $type );
    } // catch
  } // OC_Shorty_Backend::registerUrl

  // OC_Shorty_Backend::registerUrl_default
  static function registerUrl_default ( $key, $relay )
  {
    return OC_Shorty_Type::validate ( OC_Shorty_Type::URL, $relay );
  } // OC_Shorty_Backend::registerUrl_default
  
  // OC_Shorty_Backend::registerUrl_static
  static function registerUrl_static ( $key, $relay )
  {
    $base = trim ( OC_Preferences::getValue('shorty','backend-static-base','') );
    return OC_Shorty_Type::validate ( OC_Shorty_Type::URL, $base.$key );
  } // OC_Shorty_Backend::registerUrl_static
  
  // OC_Shorty_Backend::registerUrl_google
  static function registerUrl_google ( $key, $relay )
  {
    $curl = curl_init ( );
    curl_setopt ( $curl, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url' );
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, TRUE );
    curl_setopt ( $curl, CURLOPT_POST, TRUE );
    curl_setopt ( $curl, CURLOPT_POSTFIELDS, array('long_url'=>$relay,
                                                   'key'=>OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-google-key','')) );
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    if (  (FALSE===($reply=curl_exec($curl)))
        ||( ! preg_match( '/^(.+)$/', $reply, $matches )) )
      throw new OC_Shorty_Exception ( 'Failed to register url at backend' );
    curl_close ( $curl );
    return OC_Shorty_Type::validate ( OC_Shorty_Type::URL, $match[1] );
  } // OC_Shorty_Backend::registerUrl_google
  
  // OC_Shorty_Backend::registerUrl_tinyurl
  static function registerUrl_tinyurl ( $key, $relay )
  {
    $curl = curl_init ( );
    curl_setopt ( $curl, CURLOPT_URL, sprintf('http://tinyurl.com/api-create.php?url=%s', urlencode(trim($relay))) );
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    if (  (FALSE===($reply=curl_exec($curl)))
        ||( ! preg_match( '/^(.+)$/', $reply, $matches )) )
      throw new OC_Shorty_Exception ( 'Failed to register url at backend' );
    curl_close ( $curl );
    return OC_Shorty_Type::validate ( OC_Shorty_Type::URL, $match[1] );
  } // OC_Shorty_Backend::registerUrl_tinyurl
  
  // OC_Shorty_Backend::registerUrl_isgd
  static function registerUrl_isgd ( $key, $relay )
  {
    $curl = curl_init ( );
    curl_setopt ( $curl, CURLOPT_URL, sprintf('http://is.gd/create.php?format=simple&url=%s', urlencode(trim($relay))) );
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    if (  (FALSE===($reply=curl_exec($curl)))
        ||( ! preg_match( '/^(.+)$/', $reply, $matches )) )
      throw new OC_Shorty_Exception ( 'Failed to register url at backend' );
    curl_close ( $curl );
    return OC_Shorty_Type::validate ( OC_Shorty_Type::URL, $match[1] );
  } // OC_Shorty_Backend::registerUrl_isgd
  
  // OC_Shorty_Backend::registerUrl_bitly
  static function registerUrl_bitly ( $key, $relay )
  {
    $curl = curl_init ( );
    curl_setopt ( $curl, CURLOPT_URL, sprintf('http://is.gd/create.php?format=simple&login=%s&apiKey=%s&url=%s',
                                              urlencode(OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-bitly-user','')),
                                              urlencode(OC_Preferences::getValue(OC_User::getUser(),'shorty','backend-bitly-key','')),
                                              urlencode(trim($relay))) );
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    if (  (FALSE===($reply=curl_exec($curl)))
        ||( ! preg_match( '/^(.+)$/', $reply, $matches )) )
      throw new OC_Shorty_Exception ( 'Failed to register url at backend' );
    curl_close ( $curl );
    return OC_Shorty_Type::validate ( OC_Shorty_Type::URL, $match[1] );
  } // OC_Shorty_Backend::registerUrl_bitly

} // class OC_Shorty_Backend
