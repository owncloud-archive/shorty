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
 * @file plugin/catalog.php
 * Routines to retrieve meta information about a remote url
 * @author Christian Reiner
 */

namespace OCA\Shorty\Plugin;

/**
 * @class Document
 * @implements Plug
 * @brief A catalog document based on documents registered by plugins
 * @access public
 * @author Christian Reiner
 */
abstract class Document implements Plug
{
	const KEY        = null;
	const TITLE      = null;
	const POSITION   = 0;

	static public function getKey()      { return static::KEY; }
	static public function getPosition() { return static::POSITION; }
	static public function getTitle()    { return static::TITLE; }
	abstract protected function getContent();
	abstract protected function getBreadcrumbs();
	abstract public    function renderContent($slug);

	public function toArray() {
		return [
			'key'         => $this->getKey(),
			'title'       => $this->getTitle(),
			'position'    => $this->getPosition(),
			'content'     => $this->getContent(),
			'breadcrumbs' => $this->getBreadcrumbs(),
		];
	}
}