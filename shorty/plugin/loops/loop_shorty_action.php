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
 * @file plugin/loops/loop_shorty_action.php
 * Static class providing routines to populate hooks called by other parts of ownCloud
 * @author Christian Reiner
 */

namespace OCA\Shorty\Plugin;
use OCA\Shorty\L10n;

/**
 * @class OC_ShortyTracking_Loop_ListActionClicks
 * @extends \OCA\Shorty\Plugin\Loop
 * @brief Static 'namespace' class for api hook population
 * @access public
 * @author Christian Reiner
 */
class LoopShortyAction extends Loop
{
	const ACTION_NAME      = null;
	const ACTION_ICON      = null;
	const ACTION_CALLBACK  = null;
	const ACTION_TITLE     = null;
	const ACTION_ALT       = null;

	public function getActionName()     { return static::ACTION_NAME; }
	public function getActionIcon()     { return \OCP\Util::imagePath(static::LOOP_APP, static::ACTION_ICON); }
	public function getActionCallback() { return static::ACTION_CALLBACK; }
	public function getActionTitle()    { return L10n::t(static::ACTION_TITLE); }
	public function getActionAlt()      { return L10n::t(static::ACTION_ALT); }
}
