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
 * @file plugin/loops/loop_include.php
 * Static class providing routines to populate hooks called by other parts of ownCloud
 * @author Christian Reiner
 */

namespace OCA\Shorty\Tracking\Loop;
use OCA\Shorty\Tracking\L10n;

/**
 * @class \OCA\Shorty\Tracking\Loop\AppDetails
 * @extends \OCA\Shorty\Plugin\LoopAppDetails
 * @brief Represents an apps details and description
 * @access public
 * @author Christian Reiner
 */
class AppDetails extends \OCA\Shorty\Plugin\LoopAppDetails
{
	public function __construct()
	{
		static::$DETAIL_KEY      = 'shorty_tracking';
		static::$DETAIL_NAME     = "Shorty Tracking";
		static::$DETAIL_ABSTRACT = L10n::t("Detailed tracking of all requests to existing Shortys along with an integrated visualization of the access history.");
	}
}
