<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011 Christian Reiner <foss@christian-reiner.info>
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

<div id='notification'></div>
<div id="controls" class="controls shorty-controls">
  <input type="button" id="controls_button_add" value="<?php echo OC_Shorty_L10n::t('Shorten Url'); ?>"/>
<?php require_once('tmpl_url_add.php'); ?>
<?php require_once('tmpl_url_show.php'); ?>
<?php require_once('tmpl_url_edit.php'); ?>
</div>

<div id="desktop" class="right-content shorty-desktop">
<?php require_once('tmpl_url_add.php'); ?>
</div>
