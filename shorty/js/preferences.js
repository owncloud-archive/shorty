/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2014 Christian Reiner <foss@christian-reiner.info>
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

/**
 * @file js/preferences.js
 * @brief Client side activity initialization for the user preferences dialog
 * @description
 * This script takes care if initializing the client side reactions to events
 * during an opened (user) preferences dialog. Shorty follows the paradigm of
 * change-and-store, so all inputs and changes are stored right away, no
 * specific 'save' button has to be used, nor does it exist.
 * @author Christian Reinerpreferences
 */

$(document).ready(function(){
	// backend preferences, activate hints for currently selected backend
	var type=$('#shorty-preferences #backend-type').val()||'';
	if (type.length){
		$('#shorty-preferences #backend-'+type).show();
	}
	// backend 'static': initialize example that depends on backend-base system setting
	if ($('#shorty-preferences #backend-static #backend-static-base').val().length)
		$('#shorty-preferences #backend-static #example').text($('#shorty-preferences #backend-static #backend-static-base').val()+'<shorty id>');
	// backend 'static': offer a clickable example link to verify the correct setup
	$('#shorty-preferences #backend-static #example').bind('click',function(event){
		event.preventDefault();
		OC.Shorty.Action.Setting.verify();
	});
	// react with a matching explanation and example url when backend type is chosen
	$('.chosen').chosen();
	$('#shorty-preferences #backend-type').change(
		function(){
			var type=$('#shorty-preferences #backend-type').val();
			$('#shorty-preferences .backend-supplement').hide();
			if (type.length){
				$('#shorty-preferences .backend-supplement').filter('#backend-'+type).fadeIn('slow');
				// save preference
				OC.Shorty.Action.Preference.set($('#shorty-preferences #backend-type').serialize());
				return false;
			}
		}
	);
	// safe backend supplement preferences
	$('#shorty-preferences .backend-supplement').focusout(function(){
		// save preference
		OC.Shorty.Action.Preference.set($(this).find('input').serialize());
	});
	// safe ssl-verification preference
	var ssl=$('#shorty-preferences #backend-ssl-verify')
	ssl.change(function(){
		if (ssl.is(':checked'))
			OC.Shorty.Action.Preference.set('backend-ssl-verify=1');
		else OC.Shorty.Action.Preference.set('backend-ssl-verify=0');
	});
	// save scalar preferences: default-status, sms-control, verbosity-control, verbosity-timeout
	$('#shorty-preferences #sms-control,#shorty-preferences #verbosity-control,#shorty-preferences #verbosity-timeout').change(function(){
		OC.Shorty.Action.Preference.set($(this).serialize());
	});
	$('#shorty-preferences #default-status').change(function(){
		OC.Shorty.Action.Preference.set($(this).serialize());
		$('#controls #dialog-add select#status').attr('data',$(this).attr('value'));
	});
});
