<?php
/**
 * @package shorty an ownCloud url shortener plugin
 * @category internet
 * @author Christian Reiner
 * @copyright 2011-2015 Christian Reiner <foss@christian-reiner.info>
 * @license GNU Affero General Public license (AGPL)
 * @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
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
 * @file book_user.php
 * This plugins help center
 * @access public
 * @author Christian Reiner
 */

namespace OCA\Shorty;

// Session checks
\OCP\App::checkAppEnabled ( 'shorty' );
\OCP\User::checkLoggedIn  ( );

$RUNTIME_NOSETUPFS = true;

\OCP\Util::addStyle  ( 'shorty', 'help' );
\OCP\Util::addScript ( 'shorty', 'util' );
//\OCP\Util::addScript ( 'shorty', 'shorty' );
\OCP\Util::addScript ( 'shorty', 'help' );

// fetch template
$tmpl = new \OCP\Template('shorty', 'tmpl_help', 'guest');
// inflate template
$tmpl->assign('library', \OCP\Util::linkTo('shorty', 'ajax/help.php'));
// render template
$tmpl->printPage();
