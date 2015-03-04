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

// a 3rdparty helper tool
\OC::$CLASSPATH['Slimdown'] = 'shorty/3rdparty/php/slimdown.php';

/**
 * @class Catalog
 * @implements Plug
 * @brief A catalog document based on documents registered by plugins
 * @access public
 * @author Christian Reiner
 */
final class Catalog extends Document
{
	const KEY        = 'catalog';
	const TITLE      = 'Shorty document collection';
	const POSITION   = 0;

	public    function getDocuments()   { return $this->documents; }
	protected function getContent()     { return $this->content; }
	protected function getBreadcrumbs() { return []; }

	private $documents = [];
	private $content   = null;

	private function readContent() {
		$content = <<< "EOF"
<h1>Overview</h1>

<h2>Abstract</h2>
{$this->readAbstract()}

<h2>List of documents</h2>
{$this->readIndex()}
EOF;
		return $content;
	}

	private function readAbstract() {
		$abstract = <<< "EOF"
<p>This is the central point to browse the documentation bundled with the Shorty app and its installed plugins.</p>
<p>The Shorty app offers a service to store, manage and use a collection of shortened links pointing to different resources in the web. The features are a combination of a centralized bookmarks collection, a URL shortener and a basic access control.</p>
<p>Currently these documents are only available in English language. This is because I hesitate to ask a full translation of these texts from the volunteer translators investing their spare time into the ownCloud project. Some additional, more technical information can be found directly inside the "doc" folder inside the installation.</p>
EOF;
		return $abstract;
	} // function readAbstract

	private function readDocuments() {
		$documents = [];
		foreach(\OCA\Shorty\Hooks::requestDocuments() as $document) {
			$documents[$document->getKey()] = $document;
		}
		return $documents;
	}

	private function readIndex() {
		$index = [];
		foreach($this->documents as $document) {
			$index[] = sprintf('<li><a data-document="%s">%s</a href="#"></li>', $document->getKey(), $document->getTitle());
		}
		return sprintf("<ol class=\"shorty-help-index\">\n%s\n</ol>\n", implode("\n", $index));
	} // function readIndex

	public function renderContent($slug) {
		$this->content = $this->readContent($slug);
	}

	public function __construct() {
		$this->documents = $this->readDocuments();
	}
} // class Catalog
