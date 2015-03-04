<?php
/**
 * @package shorty-tracking an ownCloud url shortener plugin addition
 * @category internet
 * @author Christian Reiner
 * @copyright 2012-2015 Christian Reiner <foss@christian-reiner.info>
 * @license GNU Affero General Public license (AGPL)
 * @link information http://apps.owncloud.com/content/show.php/Shorty+Tracking?content=152473
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
 * @file lib/hooks.php
 * Static class providing routines to populate hooks called by other parts of ownCloud
 * @author Christian Reiner
 */

namespace OCA\Shorty\Tracking;

/**
 * @class OC_ShortyTracking_Documentation
 * @extends \OCA\Shorty\Plugin\Document
 * @brief Static 'namespace' class for api hook population
 * ownCloud propagates to use static classes as namespaces instead of OOP.
 * This 'namespace' defines routines to populate hooks called by other parts of ownCloud
 * @access public
 * @author Christian Reiner
 */
class BookUserGuide extends \OCA\Shorty\Plugin\Book
{
	const KEY        = 'shorty-tacking';
	const TITLE      = "Shorty Tracking";
	const POSITION   = 10;

	protected function readChapters()
	{
		if (\OCP\User::checkAdminUser())
		return [
			'introduction'  => 'Introduction',
			'usage'         => 'Usage',
			'configuration' => 'Configuration',
			'legal'         => 'Legal aspects',
		];
	}

	protected function readChapter($chapter) {
		switch($chapter) {
			case 'abstract':      return $this->renderDocFile('shorty_tracking', 'ABSTRACT');
			case 'introduction':  return $this->renderDocFile('shorty_tracking', 'README');
			case 'usage':         return $this->renderDocFile('shorty_tracking', 'USAGE');
			case 'configuration': return $this->renderDocFile('shorty_tracking', 'CONFIGURATION');
			case 'legal':         return $this->renderDocFile('shorty_tracking', 'LEGAL');
		} // switch
		return null;
	} // readContent()
}
