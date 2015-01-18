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
 * @file templates/tmpl_wdg_verify.php
 * Dialog popup to validate a configured static backend base
 * @access public
 * @author Christian Reiner
 */
?>

<html>
	<head>
		<style media="screen" type="text/css" nonce="<?php echo $_['nonce']?>">
			span { display: none; }
			body[data-success="valid"]   span.valid   { display: inline; }
			body[data-success="invalid"] span.invalid { display: inline; }
			img { margin-right: 8px; }
		</style>
		<script type="text/javascript" nonce="<?php echo $_['nonce']?>">
			parent.OC.Shorty.Action.Verification.check(<?php echo $_['target']?"'".$_['target']."'":'false'; ?>);
		</script>
	</head>
	<body id="shorty-backend-static-verification-message" data-success="">
		<span class="valid"><img src="<?php echo OCP\Util::imagePath('shorty', 'status/good.svg'); ?>"><?php echo OC_Shorty_L10n::t("Setup valid and usable"); ?></span>
		<span class="invalid"><img src="<?php echo OCP\Util::imagePath('shorty', 'status/bad.svg'); ?>"><?php echo OC_Shorty_L10n::t("Setup invalid and not usable"); ?></span>
	</body>
</html>
