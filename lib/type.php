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
 * @brief Catalog of sql queries
 */
class OC_Shorty_Type
{
  const KEY         = 'key';
  const SORTKEY     = 'sortkey';
  const SORTVAL     = 'sortval';
  const STRING      = 'string';
  const URL         = 'url';
  const INTEGER     = 'integer';
  const FLOAT       = 'float';
  const DATE        = 'date';
  const TIMESTAMP   = 'timestamp';
  static $SORTING = array ( 'ka'=>'key',      'kd'=>'key DESC',
                            'ca'=>'created',  'cd'=>'created DESC',
                            'aa'=>'accessed', 'ad'=>'accessed DESC',
                            'ta'=>'title',    'td'=>'title DESC',
                            'ha'=>'clicks',   'hd'=>'clicks DESC',
                            'ua'=>'target',   'ud'=>'target DESC' );

  static function validate ( $value, $type )
  {
    switch ( $type )
    {
      case self::KEY:
        if ( preg_match ( '/^[a-z0-9]{10}$/i', $value ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::SORTKEY:
        if ( array_key_exists ( trim($value), self::$SORTING ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::SORTVAL:
        if ( in_array ( trim($value), self::$SORTING ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::STRING:
        if ( preg_match ( '/^.*$/', $value ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::URL:
        $pattern = '/^([a-zA-Z][a-zA-Z][a-zA-Z0-9]+)\:\/\/([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+)?)*$/';
        if ( preg_match ( $pattern, $value ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::INTEGER:
        if ( preg_match ( '/^[0-9]+$/', $value ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::FLOAT:
        if ( preg_match ( '/^[0-9]+(\.[0-9]+)?$/', $value ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::TIMESTAMP:
        if ( preg_match ( '/^[0-9]{10}$/', $value ) )
          return $value;
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
      case self::DATE:
          return strtotime ( $value );
        throw new OC_Shorty_Exception ( "invalid value '%s' for type '%s'", array( ((24<sizeof($value))?$value:substr($value,0,21).'…'),$type) );
    } // switch $type
    throw new OC_Shorty_Exception ( "unknown request argument type '%s'", array($type) );
  } // function is_valid

  static function normalize ( $value, $type )
  {
    if ( ! self::validate($value,$type) )
      throw new OC_Shorty_Exception ( "invalid value '%1\$s' for type '%2\$s'", array($value,$type) );
    switch ( $type )
    {
      case self::KEY:       return trim ( $value );
      case self::SORTKEY:   return trim ( $value );
      case self::SORTVAL:   return trim ( $value );
      case self::STRING:    return trim ( $value );
      case self::URL:       return trim ( $value );
      case self::INTEGER:   return sprintf ( '%d', $value );
      case self::FLOAT:     return sprintf ( '%f', $value );
      case self::TIMESTAMP: return trim ( $value );
      case self::DATE:      return date ( 'Y-m-d', self::validate($value,OC_Shorty_Type::DATE) );
    } // switch $type
    throw new OC_Shorty_Exception ( "unknown request argument type '%s'", array($type) );
  } // function normalize

  /**
  * @brief returns checked request argument or throws an error
  * @param arg (string) name of the request argument to get_argument
  * @param strict (bool) controls if an exception will be thrown upon a missing argument
  * @return (string) checked and prepared value of request argument
  * @throw error indicating a parameter violation
  */
  static function req_argument ( $arg, $type, $strict=FALSE )
  {
    switch ( $_SERVER['REQUEST_METHOD'] )
    {
      case 'POST':
        if ( isset($_POST[$arg]) && !empty($_POST[$arg]) )
          return self::normalize ( htmlspecialchars_decode($_POST[$arg]), $type ) ;
        elseif ( ! $strict)
          return NULL;
        throw new OC_Shorty_Exception ( "missing mandatory argument '%1s'", array($arg) );
      case 'GET':
        if ( isset($_GET[$arg]) && !empty($_GET[$arg]) )
          return self::normalize ( urldecode(trim($_GET[$arg])), $type );
        elseif ( ! $strict)
          return NULL;
        throw new OC_Shorty_Exception ( "missing mandatory argument '%1s'", array($arg) );
      default:
        throw new OC_Shorty_Exception ( "unexpected http request method '%1s'", array($_SERVER['REQUEST_METHOD']) );
    }
  } // function req_argument

} // class OC_Shorty_Query
?>
