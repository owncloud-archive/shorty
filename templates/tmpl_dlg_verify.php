<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
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

<!-- a short "things work as expected" confirmation -->
<div id="verification" style="display:none;" title="<?php echo $l->t("'Static' backend: base url verification"); ?>">
  <!-- verification-in-progress -->
  <div id="hourglass">
    <fieldset>
      <img src="<?php echo OC_Helper::imagePath('shorty', 'loading-disk.gif'); ?>">
    </fieldset>
  </div>
  <!-- success -->
  <div id="success">
    <fieldset>
      <legend><span id="title" class="title"><strong>Verification successful !</strong></span></legend>
      <?php echo $l->t("Great, your setup appears to be working fine ! <br>Requests to the configured base url '%s' are mapped to this ownClouds shorty module.",$_['backend-static-base']);?>
    </fieldset>
  </div>
  <!-- failure -->
  <div id="failure">
    <fieldset>
      <legend><span id="title" class="title"><strong>Verification failed !</strong></span></legend>
      <?php echo $l->t('Sorry, but your setup appears not be be working correctly yet.<br>'.
                       'Please check your setup and make sure that the configured url \' <a style="font-family:Monospace;">%1$s</a> \' is indeed correct '.
                       'and that all requests to it are somehow mapped to ownClouds shorty module at \' <a style="font-family:Monospace;">%2$s</a> \'.',
                       array($_['backend-static-base'],OC_Helper::linkTo('shorty','')) );?>
    </fieldset>
  </div>
</div>