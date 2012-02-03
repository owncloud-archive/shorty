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

OC::$CLASSPATH['OC_Shorty_Exception'] = 'apps/shorty/lib/exception.php';
OC::$CLASSPATH['OC_Shorty_L10n']      = 'apps/shorty/lib/l10n.php';
OC::$CLASSPATH['OC_Shorty_Meta']      = 'apps/shorty/lib/meta.php';
OC::$CLASSPATH['OC_Shorty_Query']     = 'apps/shorty/lib/query.php';
OC::$CLASSPATH['OC_Shorty_Tools']     = 'apps/shorty/lib/tools.php';
OC::$CLASSPATH['OC_Shorty_Type']      = 'apps/shorty/lib/type.php';

OC_App::register ( array ( 'order' => 71, 'id' => 'shorty', 'name' => 'Short URLs' ) );

OC_App::addNavigationEntry ( array ( 'id' => 'shorty_index', 'order' => 71, 'href' => OC_Helper::linkTo( 'shorty', 'index.php' ), 'icon' => OC_Helper::imagePath( 'shorty', 'shorty.png' ), 'name' => 'Short URLs' ) );

OC_App::registerPersonal ( 'shorty', 'settings' );

?>
