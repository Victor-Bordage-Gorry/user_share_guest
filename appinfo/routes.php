<?php

/**
 * ownCloud - User Share Guest
 *
 * @author Victor Bordage-Gorry <victor.bordage-gorry@globalis-ms.com>
 * @copyright 2016 CNRS DSI / GLOBALIS media systems
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\User_Share_Guest;

use \OCA\User_Share_Guest\App\User_Share_Guest;

$application = new User_Share_Guest();

$application->registerRoutes($this, array(
    'routes' => array(
        // GUEST CONTROLLER
        array(
            'name' => 'guest#create',
            'url' => '/create',
            'verb' => 'POST',
        ),
        array(
            'name' => 'guest#delete',
            'url' => '/delete',
            'verb' => 'POST',
        ),
        array(
            'name' => 'guest#list_guests',
            'url' => '/list',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#share_list',
            'url' => '/share_list_user',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#is_guest_creation',
            'url' => '/is_guest_creation',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#is_guest',
            'url' => '/is_guest',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#accept',
            'url' => '/accept',
            'verb' => 'POST',
        ),
        array(
            'name' => 'guest#save_admin',
            'url' => '/saveadmin',
            'verb' => 'POST',
        ),
        array(
            'name' => 'guest#add_domain',
            'url' => '/adddomain',
            'verb' => 'POST',
        ),
        array(
            'name' => 'guest#delete_domain',
            'url' => '/deletedomain',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#launch_verif',
            'url' => '/verif',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#launch_clean',
            'url' => '/clean',
            'verb' => 'GET',
        ),
        array(
            'name' => 'guest#launch_stat',
            'url' => '/stat',
            'verb' => 'GET',
        ),
    ),
));
