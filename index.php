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
 * @file index.php
 * This is the plugins central position
 * All requests to the plugin are handled by this file.
 * Exceptions: system settings and user preferences dialogs
 * @access public
 * @author Christian Reiner
 */
require_once ( '../../lib/base.php' );

OC_Util::checkAppEnabled ( 'shorty' );

OC_App::setActiveNavigationEntry ( 'shorty_index' );

// OC_Util::addScript ( 'shorty',  'debug' );
OC_Util::addScript ( 'shorty',  'shorty' );
OC_Util::addScript ( 'shorty',  'init' );
OC_Util::addScript ( 'shorty/3rdparty','jquery.tinysort.min' );

OC_Util::addStyle  ( 'shorty',  'shorty' );

// strategy:
// - first: decide which action is requested
// - second: execute that action with an optional argument provided

// defaults:
$act = 'index';
$arg = NULL;
// we try to guess what the request indicates:
// - a (shorty) id to be looked up in the database resulting in a forwarding to the stored target
// - a (target) url to be added as a new shorty
// - none of the two, so just a plain list of existing shortys
foreach ($_GET as $key=>$val) // in case there are unexpected, additional arguments like a timestamp added by some stupid proxy
{
  switch ($key)
  {
    // any recognizable argument key indicating an id to be looked up ?
    case 'id':
    case 'shorty':
    case 'ref':
    case 'entry':
      // example: http://.../shorty/index.php?id=hf732J6Dk4
      $act = 'forward';
      $arg = OC_Shorty_Type::req_argument($key,OC_Shorty_Type::ID,FALSE);
      break 2; // skip switch AND foreach
    // any recognizable argument key indicating a url to be added as new shorty ?
    case 'url':
    case 'uri':
    case 'target':
    case 'link':
      // example: http://.../shorty/index.php?url=http%...
      $act = 'acquire';
      $arg = OC_Shorty_Type::req_argument($key,OC_Shorty_Type::URL,FALSE);
      break 2; // skip switch AND foreach
    // no recognizable key but something else, hm...
    // this _might_ be some unexcepted argument, or:
    // it is an expected argument, but without recognizable key, so we try to guess by examining the content
    // we restrict this 'guessing' to cases where only a single argument is specified
    default:
      if (  (1==sizeof($_GET))  // only one single request argument
          &&( ! reset($_GET)) ) // no value, so maybe just an id
      {
        // use that source instead of $key, since $key contains replaced chars (php specific exceptions due to var name problems)
        $raw = urldecode($_SERVER['QUERY_STRING']);
        // now try to interpret its content
        if (NULL!==($value=OC_Shorty_Type::normalize($raw,OC_Shorty_Type::URL,FALSE)))
        {
          // the query string is a url, acquire it as a new shorty
          $act = 'acquire';
          $arg = $raw;
          break 2;
        }
        elseif (NULL!==($value=OC_Shorty_Type::normalize($raw,OC_Shorty_Type::ID,FALSE)))
        {
          // the query string is an id, look for a shorty to forward to
          $act = 'forward';
          $arg = $raw;
          break 2;
        }
        else
        {
          // no pattern recognized, so we assume an ordinary index action
          $act = 'index';
          break 2;
        }
      } // if
      $act='index';
      break 2;
  } // switch key
} // foreach key

// next, execute the "act" whilst considering the 'arg'
switch ($act)
{
  case 'forward': // forward to a target identified by a id
    try
    {
      // detect requested shorty id from request
      $p_id = trim ( OC_Shorty_Type::normalize($_SERVER['QUERY_STRING'],OC_Shorty_Type::ID) ) ;
      // an id was specified, look for matching entry in database
      if ( '0000000000'==$p_id )
      {
        // this is a pseudo id, used to test the setup, so return a positive message.
        OC_JSON::success ( array ( ) );
        exit();
      }
      else if ( $p_id )
      {
        $param = array
        (
//           'id' => OC_Shorty_Tools::db_escape ( $p_id ),
          'id' => $p_id,
        );
        $query  = OC_DB::prepare ( OC_Shorty_Query::URL_FORWARD );
        $result = $query->execute($param)->FetchAll();
        if ( FALSE===$result )
          throw new OC_Shorty_Exception ( "HTTP/1.0 404 Not Found", $param );
        elseif ( ! is_array($result) )
          throw new OC_Shorty_Exception ( "HTTP/1.0 404 Not Found", $param );
        elseif ( 0==sizeof($result) )
        {
          // no entry found => 404: Not Found
          header ( sprintf('Location: status.php?%s', 404) );
          exit;
        }
        elseif ( 1<sizeof($result) )
        {
          // multiple matches => 409: Conflict
          header ( sprintf('Location: status.php?%s', 409) );
          exit;
        }
        elseif ( (!array_key_exists(0,$result)) || (!is_array($result[0])) || (!array_key_exists('target',$result[0])) )
        {
          // invalid entry => 500: Internal Server Error
          header ( sprintf('Location: status.php?%s', 500) );
          exit;
        }
        elseif ( (!array_key_exists('target',$result[0])) || ('1'==$result[0]['expired']) )
        {
          // entry expired => 410: Gone
          header ( sprintf('Location: status.php?%s', 410) );
          exit;
        }
        // an usable target !
        $target = trim($result[0]['target']);
        // check status of matched entry
        switch (trim($result[0]['status']))
        {
          default:
          case 'blocked':
            // refuse forwarding => 403: Forbidden
            header ( sprintf('Location: status.php?%s', 403) );
            exit;
          case 'shared':
            // check if we are a user, deny access if not
            OC_Util::checkLoggedIn ( );
            // NO break;
          case 'public':
            // forward to target, regardless of who sends the request
            header("HTTP/1.0 301 Moved Permanently");
            // http forwarding header
            header ( sprintf('Location: %s', $target) );
        } // switch status
        // register click in shorty
        $query = OC_DB::prepare ( OC_Shorty_Query::URL_CLICK );
        $query->execute ( $param );
        exit();
      } // if id
    } catch ( OC_Shorty_Exception $e ) { header($e->getMessage()); }
    // cannot really come here, but let's stay on the safe side...
    exit();
  // =====
  case 'acquire': // add url as new shorty
    // Check if we are a user
    OC_Util::checkLoggedIn ( );
    // keep the url specified as referer, that is the one we want to store
    $_SESSION['shorty-referrer'] = $arg;
    header ( sprintf('Location: %s', OC_Helper::linkTo('shorty','',null,false)) ); // TODO index.php or not, that is the question
    exit();
  // =====
  case 'index': // action 'index': list of shortys
  default:
    // Check if we are a user
    OC_Util::checkLoggedIn ( );
    try
    {
      // is this a redirect from a call with a target url to be added ? 
      if ( isset($_SESSION['shorty-referrer']) )
      {
        // this takes care of handling the url on the client side
        OC_Util::addScript ( 'shorty', 'add' );
        // add url taked from the session vars to anything contained in the query string
        $_SERVER['QUERY_STRING'] = implode('&',array_merge(array('url'=>$_SESSION['shorty-referrer']),explode('&',$_SERVER['QUERY_STRING'])));
      }
      else
      {
        // simple desktop initialization, no special actions contained
        OC_Util::addScript ( 'shorty', 'list' );
      }
      $tmpl = new OC_Template( 'shorty', 'tmpl_index', 'user' );
      // available status (required for select filter in toolbox)
      $shorty_status['']=sprintf('- %s -',OC_Shorty_L10n::t('all'));
      foreach ( OC_Shorty_Type::$STATUS as $status )
        $shorty_status[$status] = OC_Shorty_L10n::t($status);
      $tmpl->assign ( 'shorty-status', $shorty_status );
      // any referrer we want to hand over to the browser ?
      $tmpl->assign ( 'shorty-referrer', $_SESSION['shorty-referrer'] );
      // clean up session var so that a browser reload does not trigger the same action again
      unset ( $_SESSION['shorty-referrer'] );
      $tmpl->printPage();
    } catch ( OC_Shorty_Exception $e ) { OC_JSON::error ( array ( 'message'=>$e->getTranslation(), 'data'=>$result ) ); }
} // switch

?>
