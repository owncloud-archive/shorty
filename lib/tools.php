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
 * @file lib/tools.php
 * A collection of general utility routines
 * @author Christian Reiner
 */

/**
 * @class OC_Shorty_Tools
 * @brief Collection of a few practical routines, a tool box
 * @access public
 * @author Christian Reiner
 */
class OC_Shorty_Tools
{

  /**
  * @method OC_Shorty_Tools::db_escape
  * @brief escape a value for incusion in db statements
  * @param value (string) value to be escaped
  * @returns (string) escaped string value
  * @throws OC_Shorty_Exception in case of an unknown database engine
  * @access public
  * @author Christian Reiner
  * @todo use mdb2::quote() / mdb2:.escape() instead ?
  */
  static function db_escape ( $value )
  {
    $type = OC_Config::getValue ( 'dbtype', 'sqlite' );
    switch ( $type )
    {
      case 'sqlite':
      case 'sqlite3': return sqlite_escape_string     ( $value );
      case 'mysql':   return mysql_real_escape_string ( $value );
      case 'pgsql':   return pg_escape_string         ( $value );
    }
    throw new OC_Shorty_Exception ( "unknown database backend type '%1'", array($type) );
  } // function db_escape

  /**
  * @method OC_Shorty_Tools::db_timestamp
  * @brief current timestamp as required by db engine
  * @returns (string) current timestamp as required by db engine
  * @throws OC_Shorty_Exception in case of an unknown database engine
  * @access public
  * @author Christian Reiner
  * @todo not really required any more, we rely on CURRENT_TIMESTAMP instead
  */
  static function db_timestamp ( )
  {
    $type = OC_Config::getValue( "dbtype", "sqlite" );
    switch ( $type )
    {
      case 'sqlite':
      case 'sqlite3': return "strftime('%s','now')";
      case 'mysql':   return 'UNIX_TIMESTAMP()';
      case 'pgsql':   return "date_part('epoch',now())::integer";
    }
    throw new OC_Shorty_Exception ( "unknown database backend type '%1'", array($type) );
  } // function db_timestamp

  /**
  * @method OC_Shorty_Tools::shorty_key
  * @brief Creates a random key to be used for a new shorty entry
  * @returns (string) valid and unique key
  * @access public
  * @author Christian Reiner
  */
  static function shorty_key ( )
  {
    // each shorty installation uses a (once self generated) 62 char alphabet
    $alphabet=OC_Appconfig::getValue('shorty','key-alphabet');
    if ( empty($alphabet) )
    {
      $alphabet = self::randomAlphabet(62);
      OC_Appconfig::setValue ( 'shorty', 'key-alphabet', $alphabet );
    }
    // use alphabet to generate a key being unique over time
    return self::convertToAlphabet ( str_replace(array(' ','.'),'',microtime()), $alphabet );
  } // function shorty_key

  /**
   *
   */
  static function randomAlphabet ($length)
  {
    if ( ! is_integer($length) )
      return FALSE;
    $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwz0123456789";
    for($l=0;$l<$length;$l++) $s .= $c{rand(0,strlen($c))};
    return str_shuffle($s);
  } // function randomAlphabet

  /**
  * @method OC_Shorty_Tools::convertToAlphabet
  * @brief Converts a given decimal number into an arbitrary base (alphabet)
  * @param number decimal value to be converted
  * @returns (string) converted value in string notation
  * @access public
  * @author Christian Reiner
  */
  static function convertToAlphabet ( $number, $alphabet )
  {
    $alphabetLen = strlen($alphabet);
    $decVal = (int) $number;
    $number = FALSE;
    $nslen = 0;
    $pos = 1;
    while ($decVal > 0)
    {
      $valPerChar = pow($alphabetLen, $pos);
      $curChar = floor($decVal / $valPerChar);
      if ($curChar >= $alphabetLen)
      {
        $pos++;
      } else {
        $decVal -= ($curChar * $valPerChar);
        if ($number === FALSE)
        {
          $number = str_repeat($alphabet{1}, $pos);
          $nslen = $pos;
        }
        $number = substr($number, 0, ($nslen - $pos)) . $alphabet{$curChar} . substr($number, (($nslen - $pos) + 1));
        $pos--;
      }
    }
    if ($number === FALSE) $number = $alphabet{1};
    return $number;
  }

  /**
   * @method OC_Shorty_Tools::relayUrl
   * @brief Generates a relay url for a given key acting as a href target for all backends
   * @param key (string) shorty key as shorty identification
   * @returns (string) generated absolute relay url
   * @access public
   * @author Christian Reiner
   */
  static function relayUrl ($key)
  {
    return sprintf ( '%s://%s%s', (isset($_SERVER["HTTPS"])&&'on'==$_SERVER["HTTPS"])?'https':'http',
                                  $_SERVER['SERVER_NAME'],
                                  OC_Helper::linkTo('shorty','index.php?'.$key) );
  } // function relayUrl

} // class OC_Shorty_Tools
?>
