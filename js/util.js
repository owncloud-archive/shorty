/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
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

/**
 * @file js/util.js
 * @brief Client side activity library
 * @author Christian Reiner
 */

/**
 * @function max
 * @brief Returns the max value of all elements in an array
 * author Christian Reiner
 */
Array.prototype.max = function() {
  var max = this[0];
  var len = this.length;
  for (var i = 1; i < len; i++) if (this[i] > max) max = this[i];
  return max;
}
/**
 * @function min
 * @brief Returns the min value of all elements in an array
 * author Christian Reiner
 */
Array.prototype.min = function() {
  var min = this[0];
  var len = this.length;
  for (var i = 1; i < len; i++) if (this[i] < min) min = this[i];
  return min;
}

/**
 * @function max
 * @brief max()-selector
 * @usage: var maxWidth = $("a").max(function() {return $(this).width(); });
 * @param selector jQueryObject Selector of objects whos values are to be compared
 * @return value Maximum of values represented by the selector
 */
$.fn.max = function(selector) {
  return Math.max.apply(null, this.map(function(index, el) { return selector.apply(el); }).get() );
}
/**
 * @function min
 * @brief min()-selector
 * @usage: var minWidth = $("a").min(function() {return $(this).width(); });
 * @param selector jQueryObject Selector of objects whos values are to be compared
 * @return value Minimum of values represented by the selector
 */
$.fn.min = function(selector) {
  return Math.min.apply(null, this.map(function(index, el) { return selector.apply(el); }).get() );
}

/**
 * @function executeFunctionByName
 * @brief Calls a namespaced function named by a string
 * @description This is something like phps call_user_func()...
 */
function executeFunctionByName(functionName, context /*, args */) {
  var args = Array.prototype.slice.call(arguments).splice(2);
  var namespaces = functionName.split(".");
  var func = namespaces.pop();
  for(var i = 0; i < namespaces.length; i++) {
    context = context[namespaces[i]];
  }
  return context[func].apply(this, args);
}

/**
 * @function dateExpired
 * @brief Checks if a given date has already expired
 * @param date Date to check
 * @return bool Whether or not the date has expired
 * @author Christian Reiner
 */
function dateExpired(date){
  return (Date.parse(date)<=Date.parse(Date()));
} // dateExpired

/**
 * @function jsFunctionName
 * @brief Returns the name of a specified function
 * @author Christian Reiner
 */
function jsFunctionName(func){
  var name = func.toString();
  name = name.substr('function '.length);
  name = name.substr(0, name.indexOf('('));
  return name;
} // jsFunctionName
