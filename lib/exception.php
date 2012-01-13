<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011 Christian Reiner <foss@christian-reiner.info>
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
 * app specific exception class
 */

class OC_Shorty_Exception extends Exception
{
  protected $phrase = '';
  protected $param  = array ( );

  /**
   * @brief: Constructs an exception based on a phrase and a set of parameters
   * @param phrase (string) Human readable message that should be translatable
   * @param param  (array) Set of parameters to be used as sprintf arguments to fill the phrase
   */
  public    function __construct ( $phrase, $param=array() )
  {
    if ( is_array($param) )
         $this->param = $param;
    else $this->param = array($param);
    $this->phrase  = $phrase;
//    $this->message = vsprintf ( $phrase, $this->params );
    Exception::__construct ( vsprintf($phrase,$this->param), 1 );
  }

  /**
   * @brief: Returns the translated message of the exception
   * @return (string) Translated message including the filled in set of arguments
   */
  public function getTranslation ( )
  {
    return OC_Shorty_L10n::t ( $this->phrase, $this->param );
  }

  /**
  * @brief Calls OC_JSON::error with a pretty formated version of an exception
  * @return (json) OC_JSON::error
  */
  static function JSONerror ( $e )
  {
    $title = OC_Shorty_L10n::t("Exception");
    switch ( get_class($e) )
    {
      case 'OC_Shorty_Exception':
        $message = $e->getTranslation();
        break;
      case 'PDOException':
        $message = sprintf ( OC_Shorty_L10n::t( "%s\nMessage(code): %s (%s)\nFile(line): %s (%s)\nInfo: %%s",
                                                OC_Shorty_L10n::t("Exception (%s)", get_class($e)),
                                                htmlspecialchars($e->getMessage()),
                                                htmlspecialchars($e->getCode()),
                                                htmlspecialchars($e->getFile()),
                                                htmlspecialchars($e->getLine()) ),
                             (method_exists($e,'errorInfo') ? trim($e->errorInfo()) : '-/-') );
        break;
      default:
        if ( is_a($e,'Exception') )
             $message = OC_Shorty_L10n::t("Unexpected type of exception caught");
        else $message = OC_Shorty_L10n::t("Unknown object of type '%s' thrown", get_class($e));
    } // switch
    return OC_JSON::error ( array ( 'title'   => $title,
                                    'message' => $message ) );
  } // function error

} // class OC_Shorty_Exception
?>
