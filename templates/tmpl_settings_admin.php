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

<form id="shorty">
  <fieldset class="personalblock">
    <div id="title"><strong>Shorty</strong></div>
    <div id="settings">
      <label for="backend" class="bold"><?php echo $l->t('Backend');?>:</label>
      <select id="backend-type" class="chzen-select" name="type" data-placeholder="<?php echo $l->t('Choose a type...');?>">
        <option value="none"> [ none ] </option>
        <option value="static">- static -</option>
        <option value="kickto">kick.to</option>
      </select>
      <input id="backend-base" class="chzen-input" type="text" value="" maxsize="256" data-placeholder="<?php echo $l->t('Specify a backend base...');?>">
      <br/>
      <label for="backend-hint"> </label>
      <span id="backend-hint"><?php echo $l->t('Explanation:');?>
      <span id="none"   class="explain" style="display:none;"><?php echo $l->t('Don\'t use a backend, simply generate links to ownClouds shorty module.');?></span>
      <span id="static" class="explain" style="display:none;"><?php echo $l->t('Static, rule-based backend, generate links relative to a given external url.');?></span>
      <span id="kickto" class="explain" style="display:none;"><?php echo $l->t('Use "kick.to" service, register a short link for each generated shorty.');?></span>
      </span>
      <br/>
      <label for="backend-example"> </label>
      <span id="backend-example"><?php echo $l->t('Example:');?>
        <span id="none"   class="example" style="display:none;">
          <?php echo sprintf('http://%s%s<em>&lt;shorty key&gt;</em>',$_SERVER['SERVER_NAME'],OC_Helper::linkTo('shorty','',false)) ?>
        </span>
        <span id="static" class="example" style="display:none;">
          <?php echo sprintf('http://%s/<em>&lt;service&gt;</em>/<em>&lt;shorty key&gt;</em>',$_SERVER['SERVER_NAME']) ?>
        </span>
        <span id="kickto" class="example" style="display:none;">
          <?php echo sprintf('http://kick.to/<em>&lt;key&gt;</em>') ?>
        </span>
      </span>
    </div>
  </fieldset>
</form>
