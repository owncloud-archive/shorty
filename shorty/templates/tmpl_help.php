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
?>

<?php
/**
 * @file templates/tmpl_help.php
 * Allows to browse and display the bundled documentation files
 * @access public
 * @author Christian Reiner
 */
?>

<div id="shorty-help" class="shorty-dialog shorty-standalone shorty-help" data-library="<?php echo p($_['library']) ?>">

	<ul id="shorty-help-breadcrumbs" class="shorty-help-breadcrumbs">
		<li><a data-document="">Overview</a></li>
	</ul>

	<h1 id="shorty-help-title" class="shorty-help-document"></h1>
	<div id="shorty-help-content" class="shorty-help-document"></div>

</div>
