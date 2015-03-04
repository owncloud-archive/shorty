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
 * @file templates/tmpl_index.php
 * The general html environment where specific templates are embedded into.
 * @access public
 * @author Christian Reiner
 */

namespace OCA\Shorty;
?>

<!-- central messenger area -->
<div id="shorty-messenger" class="shorty-messenger" style="z-index:9250;">
	<fieldset>
		<img id="close" title="" class="svg" src="<?php p(\OCP\Util::imagePath('shorty','actions/shade.svg')); ?>">
		<img id="symbol" title="" src="">
		<span id="title"></span>
		<img id="symbol" title="" src="">
		<hr>
		<div id="message"></div>
	</fieldset>
</div>

<!-- top control bar -->
<div id="controls" class="shorty-controls" data-referrer="<?php if (array_key_exists('shorty-referrer',$_)) p($_['shorty-referrer']); ?>">
	<!-- controls: left area, buttons -->
	<div class="shorty-controls-left">
		<!-- button to add a new entry to list -->
		<button id="add" class="shorty-config settings" title="<?php p(L10n::t('New Shorty')); ?>"><?php p(L10n::t('New Shorty')); ?></button>
	</div>
	<!-- controls: right area, buttons -->
	<div class="shorty-controls-right">
		<!-- the internal settings button -->
		<button id="controls-preferences" class="shorty-config settings" title="<?php p(L10n::t('Preferences')); ?>">
			<img class="svg" src="<?php p(\OCP\Util::imagePath('shorty', 'actions/preferences.svg')); ?>"
				alt="<?php p(L10n::t('Preferences')); ?>" />
		</button>
		<!-- a container that will hold the preferences dialog -->
		<div id="appsettings" class="popup topright hidden"></div>
		<!-- the 'help' button shows the internal help system in a separate window / tab -->
		<a href="<?php p(\OCP\Util::linkToAbsolute('shorty', 'help.php')) ?>" target="_blank">
			<button id="controls-help" class="shorty-config settings" title="<?php p(L10n::t('Help')); ?>">
				<img class="svg" src="<?php p(\OCP\Util::imagePath('shorty', 'actions/help.svg')); ?>"
					alt="<?php p(L10n::t('Help')); ?>" />
			</button>
		</a>
		<!-- the 'refresh' button -->
		<a>
			<button id="controls-refresh" class="shorty-config settings" title="<?php p(L10n::t('Refresh')); ?>">
				<img class="svg" src="<?php p(\OCP\Util::imagePath('shorty','actions/refresh.svg')); ?>"
					alt="<?php p(L10n::t('Refresh')); ?>" />
			</button>
		</a>
		<!-- handle to hide/show the panel -->
		<span id="controls-handle" class="shorty-handle shorty-handle-top">
			<img class="shorty-icon svg" src="<?php p(\OCP\Util::imagePath('shorty','actions/shade.svg')); ?>" >
		</span>
	</div>
	<!-- controls: center area, some  passive information -->
	<div class="shorty-controls-center">
		<!-- display label: number of entries in list -->
		<span class="shorty-prompt"><?php p(L10n::t('Number of entries')); ?>:</span>
		<span id="sum_shortys" class="shorty-value">
			<img src="<?php p(\OCP\Util::imagePath('core', 'loading.gif')); ?>"
				class="shorty-icon" alt="<?php p(L10n::t('Loading')); ?>…"/>
		</span>
		<!-- display label: total of clicks in list -->
		<span class="shorty-prompt"><?php p(L10n::t('Total of clicks')); ?>:</span>
		<span id="sum_clicks" class="shorty-value">
			<img src="<?php p(\OCP\Util::imagePath('core', 'loading.gif')); ?>"
				class="shorty-icon" alt="<?php p(L10n::t('Loading')); ?>…" />
		</span>
	</div>
	<!-- the dialogs, hidden by default -->
	<?php echo $this->inc('tmpl_url_add'); ?>
	<?php echo $this->inc('tmpl_url_edit'); ?>
	<?php echo $this->inc('tmpl_url_show'); ?>
	<?php echo $this->inc('tmpl_url_share'); ?>
</div>

<!-- the "desktop where the action takes place -->
<div id="desktop" class="right-content shorty-desktop">
	<?php echo $this->inc('tmpl_url_list'); ?>
</div>
