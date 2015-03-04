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
 * @file plugin/book_admin.php
 * @brief
 * @author Christian Reiner
 */

namespace OCA\Shorty;

/**
 * @class BookAdminGuide
 * @extends \OCA\Shorty\Plugin\Document
 * @brief Class representing a document for visualization in the help center
 * @access public
 * @author Christian Reiner
 */
class BookAdminGuide extends Plugin\Book
{
	const KEY        = 'shorty-admin-guide';
	const TITLE      = "Shorty administrators guide";
	const POSITION   = 80;

	protected function readChapters()
	{
		return [
			'installation'  => 'Installation',
			'configuration' => 'Configuration',
			'changelog'     => 'Changelog',
			'issues'        => 'Issues',
			'roadmap'       => 'Roadmap',
			'contributions' => 'Contributions',
			'credits'       => 'Credits',
			'license'       => 'License',
		];
	}

	protected function readChapter($chapter) {
		switch($chapter) {
			case 'abstract':      return $this->renderDocFile('shorty', 'ABSTRACT');
			case 'introduction':  return $this->renderDocFile('shorty', 'README');
			case 'usage':         return $this->renderDocFile('shorty', 'USAGE');
			case 'configuration': return $this->renderDocFile('shorty', 'CONFIGURATION');
		} // switch
		return null;
	} // readContents()
} // class BookUserGuide
