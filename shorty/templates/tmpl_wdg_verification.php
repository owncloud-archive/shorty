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

<html data-verification-target="" data-verification-state="" data-verification-instance="<?php p($_['instance'])?>">
	<head>
		<link rel="stylesheet" href="<?= $_['style']?>" media="screen" type="text/css" />
		<script type="text/javascript" src="<?= $_['script']?>"></script>
	</head>
	<body>
		<span class="shorty-verification-active"><img src="<?php p(OCP\Util::imagePath('shorty', 'loading-led.gif')); ?>"></span>
		<span class="shorty-verification-valid"><img src="<?php echo OCP\Util::imagePath('shorty', 'status/good.svg'); ?>"><?php echo OC_Shorty_L10n::t("Setup valid and usable"); ?></span>
		<span class="shorty-verification-invalid"><img src="<?php echo OCP\Util::imagePath('shorty', 'status/bad.svg'); ?>"><?php echo OC_Shorty_L10n::t("Setup invalid and not usable"); ?></span>
	</body>
</html>
