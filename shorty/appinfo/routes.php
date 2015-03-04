<?php
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
 * @file appinfo/routes.php
 * @brief Basic request routing map
 * @author Christian Reiner
 */

$this->create('shorty_index',        '/'               )->actionInclude('shorty/index.php');
$this->create('shorty_preferences',  'preferences.php' )->actionInclude('shorty/preferences.php');
$this->create('shorty_proxy',        'proxy.php'       )->actionInclude('shorty/proxy.php');
$this->create('shorty_qrcode',       'qrcode.php'      )->actionInclude('shorty/qrcode.php');
$this->create('shorty_query',        'query.php'       )->actionInclude('shorty/query.php');
$this->create('shorty_relay',        'relay.php'       )->actionInclude('shorty/relay.php');
$this->create('shorty_settings',     'settings.php'    )->actionInclude('shorty/settings.php');
$this->create('shorty_verification', 'verification.php')->actionInclude('shorty/verification.php');
$this->create('shorty_help',         'help.php'        )->actionInclude('shorty/help.php');

$this->create('shorty_ajax_add',          'ajax/add.php'         )->actionInclude('shorty/ajax/add.php');
$this->create('shorty_ajax_click',        'ajax/click.php'       )->actionInclude('shorty/ajax/click.php');
$this->create('shorty_ajax_count',        'ajax/count.php'       )->actionInclude('shorty/ajax/count.php');
$this->create('shorty_ajax_del',          'ajax/del.php'         )->actionInclude('shorty/ajax/del.php');
$this->create('shorty_ajax_edit',         'ajax/edit.php'        )->actionInclude('shorty/ajax/edit.php');
$this->create('shorty_ajax_list',         'ajax/list.php'        )->actionInclude('shorty/ajax/list.php');
$this->create('shorty_ajax_meta',         'ajax/meta.php'        )->actionInclude('shorty/ajax/meta.php');
$this->create('shorty_ajax_preferences',  'ajax/preferences.php' )->actionInclude('shorty/ajax/preferences.php');
$this->create('shorty_ajax_requesttoken', 'ajax/requesttoken.php')->actionInclude('shorty/ajax/requesttoken.php');
$this->create('shorty_ajax_settings',     'ajax/settings.php'    )->actionInclude('shorty/ajax/settings.php');
$this->create('shorty_ajax_status',       'ajax/status.php'      )->actionInclude('shorty/ajax/status.php');
$this->create('shorty_ajax_help',         'ajax/help.php'        )->actionInclude('shorty/ajax/help.php');
