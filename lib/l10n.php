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
 * Translation singleton
 */

class OC_Shorty_L10n
{
  private $dictionary;
  static private $instance=NULL;
  private function __construct ( ) { $this->dictionary = new OC_L10n('shorty'); }
/*
  static public function t ( $phrase, $param=array() )
  {
    if ( ! self::$instance )
      self::$instance = new OC_Shorty_L10n ( );
    return self::$instance->dictionary->t ( $phrase, $param );
  }
*/
  static public function t ( $phrase )
  {
    // create singleton instance, if required
    if ( ! self::$instance )
      self::$instance = new OC_Shorty_L10n ( );
    // handle different styles of how arguments can be handed over to this method
    switch ( func_num_args() )
    {
      case 1:   return self::$instance->dictionary->t ( $phrase, array() );
      case 2:   $arg = func_get_arg(1);
                if ( is_array($arg) )
                     return self::$instance->dictionary->t ( $phrase, $arg );
                else return self::$instance->dictionary->t ( $phrase, array($arg) );
      default:  $args = func_get_args();
                array_shift ( $args );
                return self::$instance->dictionary->t ( $phrase, $args );
    }
  }
} // class OC_Shorty_L10n
?>
