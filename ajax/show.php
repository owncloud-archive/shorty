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

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

require_once ( '../../../lib/base.php' );

// Check if we are a user
OC_JSON::checkLoggedIn ( );
OC_JSON::checkAppEnabled ( 'shorty' );

try
{
  // read url from database
  $p_key   = OC_Shorty_Type::req_argument ( 'key',   OC_Shorty_Type::KEY,    TRUE );
  $param = array
  (
    'user'  => OC_User::getUser ( ),
    'key'   => OC_Shorty_Tools::db_escape ( $p_key ),
  );
  $query = OC_DB::prepare ( OC_Shorty_Query::URL_VERIFY );
  $url = $query->execute ( $param );
/*
  // call template to render url
  $tmpl = new OC_Template ( 'shorty', 'tpl_url_show', 'user' );
  $tmpl->assign ( 'key',      htmlentities($url['key'])      );
  $tmpl->assign ( 'source',   htmlentities($url['source'])   );
  $tmpl->assign ( 'target',   htmlentities($url['target'])   );
  $tmpl->assign ( 'notes',    htmlentities($url['notes'])    );
  $tmpl->assign ( 'created',  htmlentities($url['created'])  );
  $tmpl->assign ( 'accessed', htmlentities($url['accessed']) );
  $tmpl->assign ( 'until',    htmlentities($url['until'])    );
  $tmpl->assign ( 'clicks',   htmlentities($url['clicks'])   );
  $payload = $tmpl->fetchPage();
*/
  OC_JSON::success ( array ( 'data' => $url ) );
} catch ( Exception $e ) { OC_Shorty_Exception::JSONerror($e); }
?>
