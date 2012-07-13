<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty
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
 * @file templates/tmpl_dlg_sms.php
 * Dialog popup to prepare sending a url via sms
 * @access public
 * @author Christian Reiner
 */
?>

<!-- begin of sms dialog -->
<div id="dialog-sms" style="display:none;">
  <fieldset class="">
    <legend><?php echo $l->t("Prepare to send as SMS");?>:</legend>
    <div class="usage-explanation">
      <?php echo $l->t("Clicking 'Ok' below will try to launch an sms composer");?>.
      <br>
      <?php echo $l->t("Typically this only works on devices like smart phones");?>.
      <br>
      <?php echo $l->t("Unfortunately the implementation of this scheme is limited");?>,
      <?php echo $l->t("therefore the content must be copied manually");?>:
    </div>
    <textarea id="payload" readonly></textarea>
    <div class="usage-instruction">
      <?php echo $l->t("Copy to clipboard");?>:<span class="usage-token"><?php echo $l->t("Ctrl-C");?></span>
      <br>
      …<?php echo $l->t("after the SMS has been launched");?>…
      <br>
      <?php echo $l->t("then paste into message");?>:<span class="usage-token"><?php echo $l->t("Ctrl-V");?></span>
    </div>
  </fieldset>
</div>
<!-- end of sms dialog -->
