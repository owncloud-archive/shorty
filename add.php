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

require_once('../../lib/base.php');

// Check if we are a user
OC_Util::checkLoggedIn ( );
OC_Util::checkAppEnabled ( 'shorty' );

OC_App::setActiveNavigationEntry( 'shorty_index' );

OC_Util::addScript ( 'shorty', 'url_add' );
OC_Util::addStyle  ( 'shorty', 'shorty' );

try
{
  $p_url  = OC_Shorty_Type::req_argument('url',OC_Shorty_Type::URL,FALSE);

  $tmpl = new OC_Template( 'shorty', 'tmpl_url_add', 'user' );
  $tmpl->assign('URL',   htmlentities($p_url));
  $tmpl->printPage();
} catch ( OC_Wiki_Exception $e ) { OC_JSON::error ( array ( 'message'=>$e->getTranslation(), 'data'=>$result ) ); }
?>
