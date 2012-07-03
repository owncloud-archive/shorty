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
 * @file lib/l10n.php
 * Translation singleton
 * @author Christian Reiner
 */

/**
 * @class OC_ShortyTracking_L10n
 * @brief Convenient translation singleton, based on the class in the Shorty app
 * @access public
 * @author Christian Reiner
 */
class OC_ShortyTracking_L10n extends OC_Shorty_L10n
{
  /**
   * @var OC_ShortyTracking_L10n::dictionary
   * @brief An internal dictionary file filled from the translation files provided.
   * @access private
   * @author Christian Reiner
   */

  /**
   * @method OC_Shorty_L10n::__construct
   * @brief
   * @access private
   * @author Christian Reiner
   */
  private function __construct ( $app='shorty-tracking' ) { OC_Shorty_L10n::__construct($app); }

  } // class OC_ShortyTracking_L10n
?>
