<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2013 Christian Reiner <foss@christian-reiner.info>
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
		<link rel="stylesheet" href="<?php p(OCP\Util::linkTo('shorty','css/shorty.css'));?>" type="text/css" media="screen">
		<link rel="stylesheet" href="<?php p(OCP\Util::linkTo('shorty','css/verification.css'));?>" type="text/css" media="screen">
		<script type="text/javascript" src="<?php p(OCP\Util::linkToRemote('core.js'));?>"></script>
		<script type="text/javascript" src="<?php p(OCP\Util::linkTo('core','js/config.js'));?>"></script>
		<script type="text/javascript" src="<?php p(OCP\Util::linkTo('shorty','js/shorty.js'));?>"></script>
		<script type="text/javascript" src="<?php p(OCP\Util::linkTo('shorty','js/verification.js'));?>"></script>
	</head>
	<body>
<!-- a (usually hidden) dialog used for verification of the correct setup of the 'static' backend -->
<div id="dialog-verification" title="<?php p(OC_Shorty_L10n::t("Static backend: base url verification")); ?>">
	<!-- success -->
	<div id="success">
		<fieldset>
			<legend>
				<img class="shorty-status" src="<?php p(OCP\Util::imagePath('shorty','status/good.png')); ?>" alt="<?php OC_Shorty_L10n::t('Success') ?>" title="<?php OC_Shorty_L10n::t('Verification successful') ?>">
				<span id="title" class="shorty-title"><strong><?php p(OC_Shorty_L10n::t("Verification successful")); ?>!</strong></span>
			</legend>
			<p><?php	p(OC_Shorty_L10n::t("Great, your setup appears to be working fine!")); ?></p>
			<p><?php	p(OC_Shorty_L10n::t("Requests to the configured base url are mapped to this ownClouds relay service.").' ');
						p(OC_Shorty_L10n::t("Usage of that static backend is fine and safe as long as this setup is not altered.")); ?></p>
			<p><?php	p(OC_Shorty_L10n::t("This backend will now be offered as an additional backend alternative to all local users inside their personal preferences.")); ?></p>
		</fieldset>
	</div>
	<!-- failure -->
	<div id="failure">
		<fieldset>
			<legend>
				<img class="shorty-status" src="<?php p(OCP\Util::imagePath('shorty','status/bad.png')); ?>" alt="<?php OC_Shorty_L10n::t('Success') ?>" title="<?php OC_Shorty_L10n::t('Verification successful') ?>">
				<span id="title" class="shorty-title"><strong><?php p(OC_Shorty_L10n::t("Verification failed")); ?>!</strong></span>
			</legend>
			<p><?php	p(OC_Shorty_L10n::t("Sorry, but your setup appears not to be working correctly yet!")); ?></p>
			<p><?php	p(OC_Shorty_L10n::t("Please check your setup and make sure that the configured base url is indeed correct.").' ');
						p(OC_Shorty_L10n::t("Make sure that all requests to it are somehow mapped to Shortys relay service.")); ?></p>
			<p><?php	p(OC_Shorty_L10n::t("Relay service")); ?>:
			<br>
			<a><?php	p(OCP\Util::linkToAbsolute('','public.php?service=shorty_relay&id=')."<shorty-key>"); ?></a></p>
		</fieldset>
	</div>
</div>
	</body>
</html>
<!-- end of verification dialog -->
