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
 * @file
 * a collection of general utility routines
 */


class OC_Shorty_Tools
{

    /**
    * @brief escape a value for incusion in db statements
    * @param value (string) value to be escaped
    * @return (string) escaped string value
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
    * @brief current timestamp as required by db engine
    * @return (string) current timestamp as required by db engine
    * @TODO not really required any more, we rely on CURRENT_TIMESTAMP instead
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
    * @brief Creates a random key to be used for a new shorty entry
    * @return (string) valid and unique key
    */
    static function shorty_key ( )
    {

      return self::convertToAlphabet ( str_replace(array(' ','.'),'',microtime()),
                                       '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    } // function shorty_key

    /**
    * @brief Converts a given decimal number into an arbitrary base (alphabet)
    * @param  number decimal value to be converted 
    * @return (string) converted value in string notation
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

} // class OC_Shorty_Tools
?>
