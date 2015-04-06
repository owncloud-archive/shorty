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
 * @file plugin/atom/atom_shorty.php
 * Static class providing routines to populate hooks called by other parts of ownCloud
 * @author Christian Reiner
 */

namespace OCA\Shorty\Atom;

/**
 * @class AtomShorty
 * @extends \OCA\Shorty\Plugin\Atom
 * @access public
 * @author Christian Reiner
 */
class AtomShorty extends \OCA\Shorty\Plugin\Atom
{
	const ATOM_TYPE = \OCA\Shorty\Plugin\Atom::ATOM_TYPE_SHORTY;

	protected $id;
	protected $title;
	protected $target;
	protected $status;
	protected $user;
	protected $expired;

	public function getId()      { return $this->id; }
	public function getTitle()   { return $this->title; }
	public function getTarget()  { return $this->target; }
	public function getStatus()  { return $this->status; }
	public function getUser()    { return $this->user; }
	public function getExpired() { return $this->expired; }

	public function __construct($attributes) {
		$this->id      =  (string) $attributes['id'];
		$this->title   =  (string) $attributes['title'];
		$this->target  =  (string) $attributes['target'];
		$this->status  =  (string) $attributes['status'];
		$this->user    =  (string) $attributes['user'];
		$this->expired = (boolean) $attributes['expired'];
	}
}
