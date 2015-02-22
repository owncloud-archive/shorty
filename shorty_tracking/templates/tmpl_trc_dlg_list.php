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
?>

<?php
/**
 * @file templates/tmpl_trc_dlg.php
 * A table to visualize the hit requests to existing Shortys
 * @access public
 * @author Christian Reiner
 */
?>

<!-- a (usually hidden) dialog used to display a list of all tracked clicks hitting a Shorty -->
<div id="shorty-tracking-list-dialog" class="shorty-dialog shorty-standalone shorty-hidden" style="max-width:80%">
	<fieldset>
		<legend>
			<a id="close" class="shorty-close-button"
				title="<?php p(OC_Shorty_L10n::t("close")); ?>">
				<img alt="<?php p(OC_Shorty_L10n::t("close")); ?>" class="svg"
					src="<?php p(OCP\Util::imagePath('shorty','actions/shade.svg')); ?>">
			</a>
			<span class="heading"><?php p(OC_ShortyTracking_L10n::t("List of tracked clicks").':');?></span>
		</legend>
		<!-- begin: the dialogs header: linguistic reference to the Shorty and the sparkline -->
		<div id="shorty-header">
			<label for="shorty-title"><?php p(OC_Shorty_L10n::t("Title")); ?>: </label>
			<span id="shorty-title" class="ellipsis shorty-tracking-reference"></span>
			<br/>
			<label for="shorty-status"><?php p(OC_Shorty_L10n::t("Status")); ?>: </label>
			<span id="shorty-status" class="shorty-tracking-reference"></span>
			<span id="stats" class="sparkline">
				<img alt="loading…" title="<?php p(OC_Shorty_L10n::t("Loading")); ?>…"
					src="<?php p(OCP\Util::imagePath('shorty', 'loading-led.gif')); ?>">
			</span>
			<hr>
		</div>
		<!-- end: the dialogs header -->
		<!-- begin: the dialogs list: contains a list header and a list body -->
		<table id="list-of-clicks" class="shorty-list shorty-collapsible">
			<!-- table header -->
			<thead>
				<tr id="list-of-clicks-titlebar" class="shorty-titlebar">
					<th id="list-of-clicks-status"  data-aspect="status">
						<div><img id="list-of-clicks-tools" class="shorty-tools svg"
								alt="toolbar" title="<?php p(OC_Shorty_L10n::t("Toggle toolbar")); ?>"
								src="<?php p(OCP\Util::imagePath('shorty','actions/unshade.svg')); ?>"
								data-unshade="actions/unshade"
								data-shade="actions/shade">
						</div>
					</th>
					<th id="list-of-clicks-result"  data-aspect="result"  class="collapsible"><div><?php p(OC_ShortyTracking_L10n::t("Result"));  ?></div></th>
					<th id="list-of-clicks-address" data-aspect="address" class="collapsible"><div><?php p(OC_ShortyTracking_L10n::t("Address")); ?></div></th>
					<th id="list-of-clicks-host"    data-aspect="host"    class="collapsible"><div><?php p(OC_ShortyTracking_L10n::t("Host"));    ?></div></th>
					<th id="list-of-clicks-user"    data-aspect="user"    class="collapsible"><div><?php p(OC_ShortyTracking_L10n::t("User"));    ?></div></th>
					<th id="list-of-clicks-time"    data-aspect="time"    class="collapsible"><div><?php p(OC_ShortyTracking_L10n::t("Time"));    ?></div></th>
					<th id="list-of-clicks-actions"><div>&nbsp;</div></th>
				</tr>
				<!-- table toolbar -->
				<tr id="list-of-clicks-toolbar" class="shorty-toolbar">
					<th id="list-of-clicks-status" data-aspect="status">
						<div style="display:none;">
							<a id="list-of-clicks-reload" class="shorty-reload">
								<img alt="<?php p(OC_Shorty_L10n::t("Reload")); ?>" title="<?php p(OC_Shorty_L10n::t("Reload list")); ?>"
									class="svg" src="<?php p(OCP\Util::imagePath('shorty','actions/reload.svg')); ?>">
							</a>
						</div>
					</th>
					<th id="list-of-clicks-result" data-aspect="result" class="collapsible">
						<div style="display:none;">
							<?php print_unescaped($this->inc('../../shorty/templates/tmpl_tools_collapsible')); ?>
							<span class="shorty-select">
								<select id="filter-result" class="shorty-filter" data-placeholder=" ">
									<?php foreach($_['shorty-result'] as $option=>$label)
										print_unescaped(sprintf("<option value=\"%s\">%s</option>\n",($option?$option:''),$label));
									?>
								</select>
								<img id="clear" alt="<?php p(OC_Shorty_L10n::t('clear')); ?>" title="<?php p(OC_Shorty_L10n::t('Clear filter')); ?>"
									class="shorty-clear svg" src="<?php p(OCP\Util::imagePath('shorty','actions/clear.svg')); ?>">
							</span>
						</div>
					</th>
					<th id="list-of-clicks-address" data-aspect="address" class="collapsible">
						<div style="display:none;">
							<?php print_unescaped($this->inc('../../shorty/templates/tmpl_tools_collapsible')); ?>
							<input id="filter-address" class="shorty-filter" type="text" value="">
							<img id="clear" alt="<?php p(OC_Shorty_L10n::t('clear')); ?>" title="<?php p(OC_Shorty_L10n::t('Clear filter')); ?>"
								class="shorty-clear svg" src="<?php p(OCP\Util::imagePath('shorty','actions/clear.svg')); ?>">
						</div>
					</th>
					<th id="list-of-clicks-host" data-aspect="host" class="collapsible">
						<div style="display:none;">
							<?php print_unescaped($this->inc('../../shorty/templates/tmpl_tools_collapsible')); ?>
							<input id="filter-host" class="shorty-filter" type="text" value="">
							<img id="clear" alt="<?php p(OC_Shorty_L10n::t('clear')); ?>" title="<?php p(OC_Shorty_L10n::t('Clear filter')); ?>"
								class="shorty-clear svg" src="<?php p(OCP\Util::imagePath('shorty','actions/clear.svg')); ?>">
						</div>
					</th>
					<th id="list-of-clicks-user" data-aspect="user" class="collapsible">
						<div style="display:none;">
							<?php print_unescaped($this->inc('../../shorty/templates/tmpl_tools_collapsible')); ?>
							<input id="filter-user" class="shorty-filter" type="text" value="">
							<img id="clear" alt="<?php p(OC_Shorty_L10n::t('clear')); ?>" title="<?php p(OC_Shorty_L10n::t('Clear filter')); ?>"
								class="shorty-clear svg" src="<?php p(OCP\Util::imagePath('shorty','actions/clear.svg')); ?>">
						</div>
					</th>
					<th id="list-of-clicks-time" data-aspect="time" class="collapsible">
						<div style="display:none;">
							<?php print_unescaped($this->inc('../../shorty/templates/tmpl_tools_collapsible')); ?>
							<input id="filter-time" class="shorty-filter" type="text" value="">
							<img id="clear" alt="<?php p(OC_Shorty_L10n::t('clear')); ?>" title="<?php p(OC_Shorty_L10n::t('Clear filter')); ?>"
								class="shorty-clear svg" src="<?php p(OCP\Util::imagePath('shorty','actions/clear.svg')); ?>">
						</div>
					</th>
				</tr>
				<!-- the 'dummy' row, a blueprint -->
				<tr class="shorty-dummy">
					<td id="list-of-clicks-status"  data-aspect="status"></td>
					<td id="list-of-clicks-result"  data-aspect="result"  class="collapsible associative"></td>
					<td id="list-of-clicks-address" data-aspect="address" class="collapsible associative"></td>
					<td id="list-of-clicks-host"    data-aspect="host"    class="collapsible associative"></td>
					<td id="list-of-clicks-user"    data-aspect="user"    class="collapsible associative"></td>
					<td id="list-of-clicks-time"    data-aspect="time"    class="collapsible"></td>
					<td id="list-of-clicks-actions" data-aspect="actions">
						<span class="shorty-actions">
							<a id="shorty-tracking-action-details" title="<?php p(OC_Shorty_L10n::t("details")); ?>" data-method="OC.Shorty.Tracking.details">
								<img class="shorty-icon svg" alt="<?php p(OC_Shorty_L10n::t("details")); ?>"
									title="<?php p(OC_Shorty_L10n::t('Show details')); ?>"
									src="<?php p(OCP\Util::imagePath('shorty','actions/info.svg')); ?>" />
							</a>
						</span>
					</td>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<!-- end: the dialogs list -->
		<!-- begin: the dialogs footer: status information and an icon to load the next chunk -->
		<div id="shorty-footer">
			<hr>
			<span id="scrollingTurn">
				<img id="load" alt="load" title="<?php p(OC_ShortyTracking_L10n::t("load")); ?>"
					class="svg" src="<?php p(OCP\Util::imagePath('shorty','actions/unshade.svg')); ?>">
			</span>
			<span style="float:left;">
				<label for="shorty-clicks"><?php p(OC_ShortyTracking_L10n::t("Clicks")); ?>: </label>
				<span id="shorty-clicks" class="shorty-tracking-reference"></span>
			</span>
			<span style="float:right;">
				<label for="shorty-until"><?php p(OC_Shorty_L10n::t("Expiration")); ?>: </label>
				<span id="shorty-until" class="shorty-tracking-reference"></span>
			</span>
		</div>
		<!-- end: the dialogs footer -->
	</fieldset>
</div>
<!-- end of clicks tracking list dialog -->
