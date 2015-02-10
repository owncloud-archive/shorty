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

/**
 * @file js/settings.js
 * @brief Client side activity initialization for the system settings dialog
 * @description
 * This script takes care if initializing the client side reactions to events
 * during an opened (system) settings dialog. Shorty follows the paradigm of
 * change-and-store, so all inputs and changes are stored right away, no
 * specific 'save' button has to be used, nor does it exist.
 * @author Christian Reiner
 */

$(document).ready(function(){

	// initialize the Agent
	OC.Shorty.Action.Verification.Agent = $('#shorty-backend-static-verification-agent')[0].contentWindow;

	// store backend default upon change
	$('#shorty-backend-default').bind('change',function(e){
		// save setting
		$.when(
			OC.Shorty.Action.Setting.set($(e.currentTarget).serialize())
		).fail(function(response){
			OC.Notification.show(response.message);
		})
		return false;
	});

    // store backend selection upon change
    $('#shorty-backend-selection input[type="checkbox"]').bind('change',function(e){
		var name = $(e.target).attr('name');
		var list = $(e.target).parent().find('input[type="checkbox"][name="'+name+'"]').map( function() {
			return this.checked?this.value:null;
		}).get();
		OC.Shorty.Backend.setSystemSelection(list);
    });

	// raise change event to initially correct the default backend
	$('#shorty-backend-selection input[type="checkbox"]').trigger('change');

	// static backend base

	// react on the result of a verification task (inside the agent/iframe)
	window.addEventListener('message', function(event) {
		if(event.origin !== window.location.origin) return;
		if(event.data !== 'data-verification-state-changed') return;
		var result = $(OC.Shorty.Action.Verification.Agent.document).find('html').attr('data-verification-state');
		OC.Shorty.Action.Verification.verified(result);
	}, false);

	// verify configuration when changed by using the verification agent
	$('#shorty-backend-static-base').bind('keyup input',function(event) {
		event.preventDefault();
		var target = $('#shorty-backend-static-base').val();
		// modify example
		$('#shorty-backend-example').text(target+'<shorty id>');
		// trigger verification of setting
		OC.Shorty.Action.Verification.verify(target);
	});

	// initialize example and first verification if some base url is initially configured
	setTimeout(function(){
		var target = $('#shorty-backend-static-base').val();
		if (target.length) {
			// modify example
			$('#shorty-backend-example').text(target+'<shorty id>');
			// trigger verification of setting
			OC.Shorty.Action.Verification.verify(target);
		}
	}, 1000);

});
