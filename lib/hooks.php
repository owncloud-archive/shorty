<?php
/**
* @package shorty-tracking an ownCloud url shortener plugin addition
* @category internet
* @author Christian Reiner
* @copyright 2012-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty-tracking
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
 * @class OC_ShortyTracking_Hooks
 * @brief Static 'namespace' class for api hook population
 * ownCloud propagates to use static classes as namespaces instead of OOP.
 * This 'namespace' defines routines to populate hooks called by other parts of ownCloud
 * @access public
 * @author Christian Reiner
 */
class OC_ShortyTracking_Hooks
{
  /**
   * @brief Deletes all alien clicks, clicks that have no corresponding Shorty any more (deleted)
   * @param paramters (array) parameters from emitted signal
   * @return bool
   * @description
   * This is a cleanup routine. It removes all previously recorded clicks from the database table that
   * do not (any more) reference an existing Shorty. Such 'stale entries' typically arise when Shortys
   * get deleted, for example because of the deletion of a user account.
   * Instead of directly targeting recorded clicks to a specific Shorty identified by its ID instead
   * this routine takes a more general approach to simply wipe all stale entries. IN addition to being
   * less complex (no ID required) this also is more stable, since also leftovers from prior operations
   * are cleaned up. 
   */
  public static function deleteShortyClicks ( $parameters )
  {
    OCP\Util::writeLog ( 'OC_ShortyTracking', 'wiping all clicks without corresponding Shorty', OCP\Util::INFO );
    $result = TRUE;
    // wipe shorty clicks
    $query = OCP\DB::prepare ( OC_ShortyTracking_Query::CLICK_WIPE );
    if ( FALSE===$query->execute() )
      $result = FALSE;
    // report completion success
    return $result;
  } // function deleteShortyClicks

  /**
   * @brief Records details of request clicks targeting existing Shortys
   * @param paramters (array) parameters from emitted signal
   * @return bool
   * @description
   * This routine accepts an associative array of attributes that describe a
   * request click to a single shorty. The speicificartion of those details
   * MUST follow a strict syntactical layout that describes as this:
   * two element must be present, called 'shorty' and 'click', both are again
   * associative arrays holding these string memebers:
   * shorty: id, ...
   * click: time, address, requester, result, ...
   */
  public static function registerClick ( $parameters )
  {
    OCP\Util::writeLog ( 'OC_ShortyTracking', sprintf("recording single click to Shorty %s: %s",
                                                      $parameters['shorty']['id'],
                                                      $parameters['shorty']['title']), OCP\Util::INFO );
    $param  = array (
      'id'        => $parameters['shorty']['id'],
      'time'      => $parameters['click']['time'],
      'address'   => $parameters['click']['address'],
      'requester' => $parameters['click']['requester'],
      'result'    => $parameters['click']['result'],
    );
    $query = OCP\DB::prepare ( OC_ShortyTracking_Query::CLICK_RECORD );
    $query->execute ( $param );
    return TRUE;
  } // function registerClick

  /**
   * @brief Registers additional includes required by this plugin
   * @param paramters (array) parameters from emitted signal
   * @return bool
   */
  public static function registerIncludes ( $parameters )
  {
    OCP\Util::writeLog ( 'OC_ShortyTracking', 'registering additional include files', OCP\Util::INFO );
//    OCP\Util::addStyle  ( 'shorty-tracking', 'tracking' );
    OCP\Util::addScript ( 'shorty-tracking', 'tracking' );
    return TRUE;
  } // function registerIncludes

  /**
   * @brief Registers additional actions as expected by the Shorty app
   * @param paramters (array) parameters from emitted signal
   * @return bool
   */
  public static function registerActions ( $parameters )
  {
    OCP\Util::writeLog ( 'OC_ShortyTracking', 'registering additional Shorty actions', OCP\Util::INFO );
    if ( ! is_array($parameters) )
    {
      return FALSE;
    }
    if ( array_key_exists('list',$parameters) && is_array($parameters['list']) )
    {
      $parameters['list'][] = array (
        'id'    => 'clicks',
        'name'  => 'clicks',
        'icon'  => OCP\Util::imagePath('shorty','actions/info.png'),
        'call'  => 'Shorty.Tracking.control',
        'title' => OC_ShortyTracking_L10n::t("Click listing"),
        'alt'   => OC_ShortyTracking_L10n::t("Clicks"),
      );
    }
    return TRUE;
  } // function registerActions

} // class OC_ShortyTracking_Hooks
