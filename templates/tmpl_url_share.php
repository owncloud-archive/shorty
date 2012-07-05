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
 * @file templates/tmpl_url_share.php
 * A dialog offering control over an entries state and offers the source url
 * @access public
 * @author Christian Reiner
 */
?>

<!-- (hidden) dialog to share a shorty from the list -->
<form id="dialog-share" class="shorty-dialog shorty-embedded">
  <fieldset>
    <legend class="">
      <a id="close" class="shorty-close-button"
        title="<?php echo OC_Shorty_L10n::t('Close'); ?>">
        <img alt="<?php echo OC_Shorty_L10n::t('Close'); ?>"
            src="<?php echo OCP\Util::imagePath('apps/shorty','actions/shade.png');  ?>">
      </a>
      <?php echo OC_Shorty_L10n::t('Share and use').':'; ?>
    </legend>
    <input id="id" name="id" type="hidden" readonly data="" class="" readonly disabled />
    <label for="status"><?php echo OC_Shorty_L10n::t('Status').':'; ?></label>
    <select id="status" name="status" data="" class="" value="">
    <?php
      foreach ( OC_Shorty_Type::$STATUS as $status )
        if ( 'deleted'!=$status )
          echo sprintf ( "<option value=\"%s\">%s</option>\n", $status, OC_Shorty_L10n::t($status) );
    ?>
    </select>
    <span id="blocked" class="status-hint" style="display:none;"><?php echo OC_Shorty_L10n::t('for any access')."."; ?></span>
    <span id="private" class="status-hint" style="display:none;"><?php echo OC_Shorty_L10n::t('for own usage')."."; ?></span>
    <span id="shared"  class="status-hint" style="display:none;"><?php echo OC_Shorty_L10n::t('with ownCloud users')."."; ?></span>
    <span id="public"  class="status-hint" style="display:none;"><?php echo OC_Shorty_L10n::t('available for everyone')."."; ?></span>
    <div class="shorty-usages">
      <fieldset class="shorty-collapsible collapsed">
        <label for="source-text"><?php echo OC_Shorty_L10n::t('Source url').':'; ?></label>
        <span id="source-text">This is the shortened url registered at the backend. </span>
        <div class="shorty-collapsible-tail" style="display:none;">
          <a id="source" class="shorty-clickable" target="_blank"
            title="<?php echo OC_Shorty_L10n::t('Open source url'); ?>"
            href=""></a>
        </div>
      </fieldset>
      <fieldset class="shorty-collapsible collapsed">
        <label for="relay-text"><?php echo OC_Shorty_L10n::t('Relay url').':'; ?></label>
        <span id="relay-text">This is the internal url the 'official' one relays to. </span>
        <div class="shorty-collapsible-tail" style="display:none;">
          <a id="relay" class="shorty-clickable" target="_blank"
            title="<?php echo OC_Shorty_L10n::t('Open relay url'); ?>"
            href=""></a>
        </div>
      </fieldset>
      <fieldset class="shorty-collapsible collapsed">
        <label for="target-text"><?php echo OC_Shorty_L10n::t('Target url').':'; ?></label>
        <span id="target-text">This is the target url specified when generating this Shorty. </span>
        <div class="shorty-collapsible-tail" style="display:none;">
          <a id="target" class="shorty-clickable" target="_blank"
            title="<?php echo OC_Shorty_L10n::t('Open target url'); ?>"
            href=""></a>
        </div>
      </fieldset>
      <br/>
    </div>
    <table class="shorty-grid">
      <tr>
        <td>
          <img id="usage-qrcode" type="image" name="usage-qrcode" class="shorty-usage" alt="qrcode"
               src="<?php echo OCP\Util::imagePath('apps/shorty','usage/64/qrcode.png'); ?>"
               title="<?php echo OC_Shorty_L10n::t("Show as QRCode"); ?>" />
        </td>
        <td>
          <img id="usage-email" name="usage-email" class="shorty-usage" alt="email"
               src="<?php echo OCP\Util::imagePath('apps/shorty','usage/64/email.png'); ?>"
               title="<?php echo OC_Shorty_L10n::t("Send by email"); ?>" />
        </td>
<?php if ('disabled'!=$_['sms-control']) { ?>
        <td>
          <img id="usage-sms" name="usage-sms" class="shorty-usage" alt="sms"
               src="<?php echo OCP\Util::imagePath('apps/shorty','usage/64/sms.png'); ?>"
               title="<?php echo OC_Shorty_L10n::t("Send by SMS"); ?>" />
        </td>
<?php } ?>
        <td>
          <img id="usage-clipboard" type="image" name="usage-clipboard" class="shorty-usage" alt="clipbaord"
               src="<?php echo OCP\Util::imagePath('apps/shorty','usage/64/clipboard.png'); ?>"
               title="<?php echo OC_Shorty_L10n::t("Copy to clipboard"); ?>" />
        </td>
      </tr>
    </table>
  </fieldset>
</form>

<!-- additional (hidden) popup dialogs for specific usage actions -->
<?php require_once('tmpl_dlg_email.php'); ?>
<?php require_once('tmpl_dlg_sms.php'); ?>
<?php require_once('tmpl_dlg_clipboard.php'); ?>
<?php require_once('tmpl_dlg_qrcode.php'); ?>
