/**
 * @package shorty an ownCloud url shortener plugin
 * @category internet
 * @author Christian Reiner
 * @copyright 2011-2013 Christian Reiner <foss@christian-reiner.info>
 * @license GNU Affero General Public license (AGPL)
 * @link information http://apps.owncloud.com/content/show.php/OC.Shorty?content=150401
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
 * @brief Client side activity library
 * @description
 * This script codes all most of internal, client side logic implemented by the
 * Shorty app. That logic may be extended by plugins registering into the app.
 * A few internal functions are called via such plugin hooks too, this allows
 * to use a uniform call scheme by plugins and this main app.
 * @author Christian Reiner
 */

// $(document).ready(function(){
// 	var target=$('#verification-target').val();
// 	check(target);
// }
// 
// $(window).load(function(){
// 	var target=$('#verification-target').val();
// 	check(target);
// }

// check:function(target){
// alert(target);
// 	var dfd = new $.Deferred();
// 	// note: this is a jsonp request, cause the static backend provider might be a separate host
// 	// to escape the cross domain protection by browsers we use the jsonp pattern
// 	$.ajax({
// 		// the '0000000000' below is a special id recognized for testing purposes
// 		url:           target+'0000000000',
// 		cache:         false,
// 		crossDomain:   true, // required when using a "short named domain" and server side url rewriting
// 		data:          { },
// 		dataType:      'jsonp',
// 		jsonp:         false,
// 		jsonpCallback: 'verifyStaticBackend',
// 		timeout:       6000 // timeout to catch silent failures, like a 404
// 	}).pipe(
// 		function(response){return OC.Shorty.Ajax.eval(response)},
// 		function(response){return OC.Shorty.Ajax.fail(response)}
// 	).done(function(response){
// 		$.when(
// 			$('#hourglass').fadeOut('fast')
// 		).then(function(){
// 			$('#success').fadeIn('fast');
// 			dfd.resolve(response);
// 		})
// 	}).fail(function(response){
// 		$.when(
// 			$('#hourglass').fadeOut('fast')
// 		).then(function(){
// 			$('#failure').fadeIn('fast');
// 			dfd.reject(response);
// 		})
// 	})
// 	return dfd.promise();
// } // OC.Shorty.Action.Setting.check

// document.load(function(){
// alert($('#verification-target').val());
alert(parent.$('#verification-target', parent.$('#static-backend-verification').contentWindow.document).val());
// alert($('#verification-target', $('#static-backend-verification').contentWindow.document).val());
// alert($('#static-backend-verification')[0].contents().find('#verification-target').val());
//alert($('#verification-target',window.parent.document.frames[0].document).val());
// check($('#verification-target').val());
// window.parent.OC.Shorty.Action.Setting.check($('#static-backend-verification'), $('#verification-target').val());
window.parent.OC.Shorty.Action.Setting.check($('#static-backend-verification'), OC.Shorty.Action.Setting.Popup.find('#verification-target').val());
// 	$('#static-backend-verification', window.parent.frames[0].document).remove();
// });
