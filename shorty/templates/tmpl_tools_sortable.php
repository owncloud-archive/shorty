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
 * @file templates/tmpl_url_list_tools_sortable.php
 * A list of tool icons to be used in list templates
 * @access public
 * @author Christian Reiner
 */

namespace OCA\Shorty;
?>

<img data-sort-code="<?php p($_['sortcol']);?>a" data-sort-type="string" data-sort-direction='asc'
	alt="<?php p(L10n::t('up'));   ?>" title="<?php p(L10n::t('Sort ascending'));  ?>"
	class="shorty-tool shorty-sort shorty-sort-up svg" src="<?php p(\OCP\Util::imagePath('shorty','actions/up.svg'));   ?>">
<img data-sort-code="<?php p($_['sortcol']);?>d" data-sort-type="string" data-sort-direction='desc'
	alt="<?php p(L10n::t('down')); ?>" title="<?php p(L10n::t('Sort descending')); ?>"
	class="shorty-tool shorty-sort shorty-sort-down svg" src="<?php p(\OCP\Util::imagePath('shorty','actions/down.svg')); ?>">
