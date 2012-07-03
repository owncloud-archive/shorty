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
 * @file lib/hooks.php
 * Static class providing routines to populate hooks called by other parts of ownCloud
 * @author Christian Reiner
 */

/**
 * @class OC_Shorty_Hooks
 * @brief Static 'namespace' class for api hook population
 * ownCloud propagates to use static classes as namespaces instead of OOP.
 * This 'namespace' defines routines to populate hooks called by other parts of ownCloud
 * @access public
 * @author Christian Reiner
 */
class OC_Shorty_Hooks
{
  /**
   * @brief Deletes all Shortys and preferences of a certain user
   * @param paramters (array) parameters from postDeleteUser-Hook
   * @return bool
   */
  public static function deleteUser ( $parameters )
  {
    OCP\Util::writeLog ( 'OC_Shorty','wiping all users Shortys', OCP\Util::INFO );
    $result = TRUE;
    $param  = array ( 'user' => OCP\User::getUser() );
    // wipe preferences
    $query = OCP\DB::prepare ( OC_Shorty_Query::WIPE_PREFERENCES );
    if ( FALSE===$query->execute($param) )
      $result = FALSE;
    // wipe shortys
    $query = OCP\DB::prepare ( OC_Shorty_Query::WIPE_SHORTYS );
    if ( FALSE===$query->execute($param) )
      $result = FALSE;
    // allow further cleanups via registered hooks
    OC_Hook::emit( "OC_Shorty", "post_deleteUser", array("user"=>$param['user']) );
    // report completion success
    return $result;
  }

  public static function requestActions ( )
  {
    OCP\Util::writeLog ( 'OR_Shorty', 'requesting actions to be offered for Shortys by other apps', OCP\Util::INFO );
    $actions = array ( 'list'=>array(), 'shorty'=>array() );
    // we hand over a container by reference and expect any app registering into this hook to obey this structure:
    // ... for every action register a new element in the container
    // ... ... such element must be an array holding the entries tested below
    $container = array ( 'list'=>&$actions['list'], 'shorty'=>&$actions['shorty'] );
    OC_Hook::emit ( 'OC_Shorty', 'registerActions', $container );
    // validate and evaluate what was returned in the $container
    if ( ! is_array($container))
    {
      OCP\Util::writeLog ( 'OC_Shorty', 'invalid reply from some app that registered into the registerAction hook, FIX THAT APP !', OCP\Util::INFO );
      return array();
    } // if
    foreach ( $container as $action )
    {
      if (  ! is_array($action)
         || ! array_key_exists('id',$action)     || ! is_string($action['id'])
         || ! array_key_exists('name',$action)   || ! is_string($action['name'])
         || ! array_key_exists('icon',$action)   || ! is_string($action['icon'])
         || ( array_key_exists('call',$action)   && ! is_string($action['call']) )
         || ( array_key_exists('title',$action)  && ! is_string($action['title']) )
         || ( array_key_exists('alt',$action)    && ! is_string($action['alt']) ) )
      {
        OCP\Util::writeLog ( 'OC_ShortyTracking', 'invalid reply from an app that registered into the registerAction hook, FIX THAT APP !', OCP\Util::INFO );
        break;
      }
    } // foreach action
    return $actions;
  } // function requestActions

  public static function requestIncludes ( )
  {
    OCP\Util::writeLog ( 'OC_Shorty', 'requesting includes registered by other apps', OCP\Util::INFO );
    OC_Hook::emit ( 'OC_Shorty', 'registerIncludes', array() );
  } // function requestIncludes

  public static function registerClick ( $shorty, $request )
  {
    // save click in the database
    $param = array (
      'id'   => $shorty['id'],
      'time' => $request['time'],
    );
    $query = OCP\DB::prepare ( OC_Shorty_Query::URL_CLICK );
    $query->execute ( $param );

    // allow further processing IF hooks are registered
    OC_Hook::emit( "OC_Shorty", "registerClick", array('shorty'=>$shorty,'click'=>$request) );
  } // function registerClick
} // class OC_Shorty_Hooks
