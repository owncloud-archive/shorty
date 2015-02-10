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
 * @file js/verification.js
 * @brief Client side validation of the base url set for the 'static backend'
 * @description
 * This script takes care of validating the setting of the 'static backend'
 * That validation is basically done by a test request using a test parameter
 * @author Christian Reiner
 */

var check = function() {
	// indicate verification activity
	var anchor = document.getElementsByTagName('html')[0];
	anchor.setAttribute('data-verification-state', 'active');
	var instance = document.getElementsByTagName('html')[0].getAttribute('data-verification-instance');
	var target = anchor.getAttribute('data-verification-target');
	var xmlhttp = new XMLHttpRequest();
	// verification request
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == XMLHttpRequest.DONE) {
			if (xmlhttp.status == 200) {
				var response;
				try {
				 	response = JSON.parse(xmlhttp.responseText);
				 	if (response.hasOwnProperty('status') &&
				 	 	response.hasOwnProperty('id') && response.id==='0000000000' &&
				 	 	response.hasOwnProperty('instance') && response.instance===instance) {
				 	 	anchor.setAttribute('data-verification-state', ('success'===response.status) ? 'valid' : 'invalid-X');
				 	} else {
				 		anchor.setAttribute('data-verification-state', 'invalid-XX');
				 	}
				} catch (e) {
					anchor.setAttribute('data-verification-state', 'invalid-XXX');
				}
			}
		}
		if ( 'active' != anchor.getAttribute('data-verification-state' )) {
			window.parent.postMessage('data-verification-state-changed', window.location);
		}
	};
	xmlhttp.open('GET', target + '0000000000', true);
	xmlhttp.send();
};

window.addEventListener("message", function(event){
	if(event.origin !== window.location.origin) return;
	if(event.data !== 'data-verification-target-changed') return;
	check();
}, false);
