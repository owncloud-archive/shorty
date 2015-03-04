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
 * @file js/help.js
 * @brief Client side desktop initialization in case of a call with an url to add
 * @description
 * The Shorty app comes with a javascript bookmarklet ('Shortlet'). This calls
 * the app and specifies a url to be shortened, this makes shortening an open
 * web pages url muhc easier: just click-and-store as Shorty. So the user does
 * not ahve to manually open his ownCloud, navigate the the Shorty app and open
 * the 'New Shorty' dialog. This script is added in case such a request is
 * detected, it takes case that the dialog is opened and filled with the url
 * to be shortened.
 * @author Christian Reiner
 */

OC.Shorty={
    Help: {
        Library: '',
        Breadcrumbs: {},
        Document: {},
        Title: {},
        Content: {},
        Catalog: {},

        setBreadcrumbs: function (breadcrumbs) {
            OC.Shorty.Help.Breadcrumbs.html($('<li>').append($('<a>').html('Shorty help center')));
            var document = [];
            $.each(breadcrumbs, function(key,title){
                document.push(key);
                OC.Shorty.Help.Breadcrumbs.append($('<li>').append($('<a>').attr('data-document', document.join(',')).html(title)));
            });
        },
        setDocument: function (title, content) {
            $.when(
                OC.Shorty.Help.Document.fadeOut()
            ).done(function(){
                OC.Shorty.Help.Title.html(title);
                OC.Shorty.Help.Content.html(content);
                OC.Shorty.Help.Document.fadeIn();
            })
        },
        fetchDocument: function (slug) {
            dfd = new $.Deferred();
            $.ajax({
                type: 'GET',
                url: OC.Shorty.Help.Library,
                cache: false,
                data: {slug: slug},
                dataType: 'json'
            }).done(function (response) {
                dfd.resolve(response);
            }).fail(function(){
                dfd.reject();
            });
            return dfd.promise();
        },
        switchDocument: function (document) {
            // always scroll view to top
            $("html, body").animate({ scrollTop: 0 }, 'slow');
            $.when(
                OC.Shorty.Help.fetchDocument(document),
                OC.Shorty.Help.Document.fadeOut()
            ).done(function(response){
                OC.Shorty.Help.setBreadcrumbs(response.breadcrumbs);
                OC.Shorty.Help.setDocument(response.title, response.content);
                OC.Shorty.Help.Document.fadeIn()
            })
        }
    } // Help
}; // OC.Shorty

$(document).ready(function() {
    OC.Shorty.Help.Library     = $('#shorty-help').data('library');
    OC.Shorty.Help.Breadcrumbs = $('#shorty-help-breadcrumbs');
    OC.Shorty.Help.Document    = $('.shorty-help-document');
    OC.Shorty.Help.Title       = $('#shorty-help-title');
    OC.Shorty.Help.Content     = $('#shorty-help-content');

    // react on clicks
    $('#shorty-help-breadcrumbs').on('click', 'li', function(e){
        e.preventDefault();
        // a click on the embedded anchor tag is ignored via css, so this is an li element that *contains* an anchor
        OC.Shorty.Help.switchDocument($(e.target).find('a').data('document'));
    });
    $('#shorty-help-content').on('click', 'a', function(e){
        e.preventDefault();
        OC.Shorty.Help.switchDocument($(e.target).data('document'));
    });

    // load initial overview
    OC.Shorty.Help.switchDocument('');

});
