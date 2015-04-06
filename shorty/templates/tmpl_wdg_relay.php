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
 * @file templates/tmpl_wdg_relay.php
 * Displays a human readable version of a classical http status error
 * @access public
 * @author Christian Reiner
 */

namespace OCA\Shorty;
?>

<div id="shorty-relay" class="shorty-dialog shorty-standalone">

	<h1><?php p(L10n::t('Shorty relay service')); ?></h1>

	<h2><?php p(L10n::t('If you proceed you will be forwarded to:')); ?></h2>

	<fieldset id="shorty-relay-object">
		<legend><span class="heading"><?php p(L10n::t('Target object').':'); ?></span></legend>

		<label for="title"><?php p(L10n::t('Title').':'); ?></label>
		<span id="title" class=""><?= $_['shorty']->getTitle() ?></span>
		<br />
		<label for="target"><?php p(L10n::t('Location').':'); ?></label>
		<span id="target" class=""><?= $_['shorty']->getTarget() ?></span>
		<hr />
		<label for="details">
			<img id="staticon"  class="shorty-icon svg" width="16px" src="<?= $_['meta']['staticon'] ?>">
			<img id="schemicon" class="shorty-icon svg" width="16pqx" src="<?= $_['meta']['schemicon'] ?>">
			<img id="favicon"   class="shorty-icon svg" width="16px" src="<?= $_['meta']['favicon'] ?>">
			<img id="mimicon"   class="shorty-icon svg" width="16px" src="<?= $_['meta']['mimicon'] ?>">
		</label>
		<span id="details" class=""><?php p($_['meta']['title']); ?></span>

	</fieldset>

	<div>
		<button id="shorty-relay-proceed" class="shorty-button-submit" data-target="<?= $_['shorty']->getTarget() ?>">
			<?php p(L10n::t('Proceed')); ?>
		</button>
	</div>

</div>
