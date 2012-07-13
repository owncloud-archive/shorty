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
 * @file templates/tmpl_dlg_qrcode.php
 * Dialog popup to visualize and offer an url as a QRCode (2D barcode)
 * @access public
 * @author Christian Reiner
 */
?>

<!-- begin of qrcode dialog -->
<div id="dialog-qrcode" style="display:none;">
  <fieldset class="">
    <legend><?php echo $l->t("Shorty as QRCode");?>:</legend>
    <input id="qrcode-url" type="hidden" value="<?php echo $_['qrcode-url']; ?>">
    <div id='qrcode-img'>
      <div class="usage-explanation">
        <?php echo $l->t("This 2d barcode encodes the url pointing to this Shorty");?>.
        <br>
        <?php echo $l->t("Use it in web pages by referencing or embedding");?>,
        <?php echo $l->t("or simpy print or download it for off-line usage");?>!
      </div>
      <div style="text-align:center;">
        <img style="width:154px;" class="usage-qrcode" alt="<?php echo $l->t("QRCode"); ?>"
            src="<?php echo OCP\Util::imagePath('shorty','loading-disk.gif'); ?>" >
        <div class="usage-instruction">
          <?php echo $l->t("Click for embedding details");?>…
        </div>
      </div>
    </div>
    <div id='qrcode-url' style="display:none;">
      <div class="usage-explanation">
        <?php echo $l->t("This is the url referencing the QRCode shown before");?>.
        <br>
        <?php echo $l->t("Copy and embed it into an img tag on some web page");?>.
      </div>
      <textarea id="payload" readonly></textarea>
      <div class="usage-instruction">
        <?php echo $l->t("Copy to clipboard");?>:<span class="usage-token"><?php echo $l->t("Ctrl-C");?></span>
        <br>
        <?php echo $l->t("Paste to embed elsewhere");?>:<span class="usage-token"><?php echo $l->t("Ctrl-V");?></span>
      </div>
      <hr>
      <div class="usage-explanation">
        <?php echo $l->t("Alternatively get the image for printout or storage");?>:
        <br>
        <div style="text-align:center;">
          <button id="download" style="margin:1.6em;" class="shorty-button">Download QRCode</button>
        </div>
      </div>
    </div>
  </fieldset>
</div>
<!-- end of qrcode dialog -->
