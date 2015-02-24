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
 * @file templates/tmpl_url_list_tools_collapsible.php
 * A list of tool icons to be used in list templates
 * @access public
 * @author Christian Reiner
 */
?>

					<img data-collapsible-code="collapse"
						alt="<?php p(OC_Shorty_L10n::t('Collapse column'));?>" title="<?php p(OC_Shorty_L10n::t('Collapse column'));  ?>"
						class="shorty-tool shorty-tool-collapsible svg" src="<?php p(OCP\Util::imagePath('shorty','actions/collapse.svg'));?>">
					<img data-collapsible-code="expand"
						alt="<?php p(OC_Shorty_L10n::t('Expand column'));  ?>" title="<?php p(OC_Shorty_L10n::t('Expand column'));  ?>"
						class="shorty-tool shorty-tool-collapsible svg" src="<?php p(OCP\Util::imagePath('shorty','actions/expand.svg'));?>">
