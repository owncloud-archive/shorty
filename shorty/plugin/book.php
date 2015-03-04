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
 * @file plugin/book.php
 * Routines to retrieve meta information about a remote url
 * @author Christian Reiner
 */

namespace OCA\Shorty\Plugin;

// a 3rdparty helper tool
\OC::$CLASSPATH['Slimdown'] = 'shorty/3rdparty/php/slimdown.php';

/**
 * @class Book
 * @implements Plug
 * @brief A document based on documentation file browsing and visualization
 * @access public
 * @author Christian Reiner
 */
abstract class Book extends Document
{
	protected $chapters    = null;
	protected $content     = null;
	protected $breadcrumbs = [];

	protected function getChapters()    { return $this->chapters; }
	protected function getContent()     { return $this->content; }
	protected function getBreadcrumbs() { return $this->breadcrumbs; }

	abstract protected function readChapters();
	abstract protected function readChapter($chapter);
	protected function readAbstract() { return $this->readChapter('abstract');}

	protected function renderDocFile($app, $name)
	{
		$path = sprintf('%s/apps/%s/doc/%s', getcwd(), $app, $name);
		return \Slimdown::render(file_get_contents($path));
	}

	protected function readIndex()
	{
		$index = [];
		foreach ($this->chapters as $key=>$title) {
			$index[] = sprintf('<li><a data-document="%s">%s</a href="#"></li>', implode(',',[$this->getKey(),$key]), $title);
		}
		return sprintf("<ol class=\"shorty-help-index\">\n%s\n</ol>\n", implode("\n", $index));
	} // function readIndex

	public function readContent($slug) {
		$chapter = array_shift($slug);
		if ($chapter) {
			$this->breadcrumbs[$chapter] = $this->chapters[$chapter];
			$content = <<< "EOF"
<h1>{$this->chapters[$chapter]}</h1>
{$this->readChapter($chapter)}
EOF;
		} else {
			$content = <<< "EOF"
<h1>Overview</h1>

<h2>Abstract</h2>
{$this->readAbstract()}

<h2>List of documents</h2>
{$this->readIndex()}
EOF;
		}
		return $content;
	}

	public function renderContent($slug) {
		$this->breadcrumbs = [$this->getKey() => $this->getTitle()];
		$this->content     = $this->readContent($slug);
	}

	final public function __construct()
	{
		$this->chapters = $this->readChapters();
	}

} // class Book
