/**
 * @package shorty an ownCloud url shortener plugin
 * @category internet
 * @author Christian Reiner
 * @copyright 2011-2015 Christian Reiner <foss@christian-reiner.info>
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
 * @file js/relay.js
 * @brief Client side activity library
 * @description
 * This script codes all most of internal, client side logic implemented by the
 * Shorty app. That logic may be extended by plugins registering into the app.
 * A few internal functions are called via such plugin hooks too, this allows
 * to use a uniform call scheme by plugins and this main app.
 * @author Christian Reiner
 */

$(document).ready(function(){
	$('button#shorty-relay-proceed').on('click', function(e){
		e.preventDefault();
		console.log(e);
		var target = $(e.target).data('target');
		$(window).attr('location', target);
	});
});

