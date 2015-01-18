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
* but WITHOUT ANY WARRANTY; without even the implied wpayloadanty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @file js/debug.js
 * @brief Client side debugging methods
 * @description
 * The (manual) inclusion of these definitions trigger additional debug output.
 * @author Christian Reiner
 */

$(document).ready(function(){
	$.extend(OC.Shorty,{
		Debug:{
			log: function(issue){
				switch (typeof(issue)){
					case 'string':
						// don't crash when no console object present (IE9 workaround)
						try { console.log(issue); } catch (error) {}
						break;
					default:
						// don't crash when no console object present (IE9 workaround)
						try { console.log(this.dump(issue)); } catch (error) {}
				} // switch
			}, // OC.Shorty.Debuglog()

			dump: function(payload,level){
				var dumped_text = "";
				if(!level) level = 0;
				// some padding given at the beginning of the line
				var level_padding = "";
				for(var j=0;j<level+1;j++) level_padding += "    ";

				if(typeof(payload) == 'object') {
					for(var item in payload) {
						var value = payload[item];

						if(typeof(value) == 'object') {
								dumped_text += level_padding + "'" + item + "' ...\n";
								dumped_text += dump(value,level+1);
							} else {
								dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
						}
					}
				} else { //Stings/Chars/Numbers etc.
					dumped_text = "==>"+payload+"<==("+typeof(payload)+")";
				}
				return dumped_text;
			} // OC.Shorty.Debug.dump()
		} // OC.Shorty.Debug
  });
});
