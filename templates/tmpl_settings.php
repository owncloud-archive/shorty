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
 * @file templates/tmpl_settings.php
 * Dialog to change plugin settings, to be included in the clouds settings page.
 * @access public
 * @author Christian Reiner
 */
?>

<!-- settings of app 'shorty' -->
<form id="shorty">
  <fieldset class="personalblock">
    <legend>
      <span id="title" class="title"><strong>Shorty</strong></span>
    </legend>
    <label for="backend-static" class="aspect"><?php echo $l->t('Backend:');?></label>
    <span id="backend-static" class="backend-supplement">
      <label for="backend-static-base-system" class="aspect"><?php echo $l->t('Base url:');?></label>
      <input id="backend-static-base-system" type="text" name="backend-static-base-system" value="<?php echo $_['backend-static-base-system']; ?>"
             maxsize="256" placeholder="<?php echo $l->t('Specify a backend base url…');?>" style="width:15em;">
      <br/>
      <label for="backend-example" class="aspect"> </label>
      <span id="backend-example">
        <label for="example" class="aspect"><?php echo $l->t('Example:');?></label>
        <span id="example" class="example" style="padding-left:0.5em;"><?php echo sprintf('http://%s/<em>&lt;service&gt;</em>/<em>&lt;shorty key&gt;</em>',$_SERVER['SERVER_NAME']) ?></span>
      </span>
      <br/>
      <span id="explain" class="explain"><?php echo $l->t(
        'Static, rule-based backend, generates shorty links relative to a given base url.<br>'.
        'You have to take care that any request to the url configured here is internally mapped to the shorty module.<br>'.
        'Have a try with the example link provided, it should result in a hint that the requested shorty is not defined.<br>'.
        'Leave empty if you can\'t provide a short base url that is mapped the described way.');?>
      </span>
    </span>
  </fieldset>
</form>
