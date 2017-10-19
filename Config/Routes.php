<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

return array(
    'resources' => array(
        'packages' => array(
            'methods' => array(
                'get' => 0, # enroutes to list
                'post' => 1, # enroute to create
                'put' => 2, # enroutes to update
                'patch' => 3, # enroutes to update
                'delete' => 4, # enroute to remove
            ),
            'dispatch' => array(
                'controller' => 'packages'
            )
        ),
        'offers' => array(
            'methods' => array(
                'get' => 0, # enroutes to list
                'post' => 1, # enroute to create
                'put' => 2, # enroutes to update
                'patch' => 3, # enroutes to update
                'delete' => 4, # enroute to remove
            ),
            'dispatch' => array(
                'controller' => 'offers'
            )
        ),
    ),
    'routes' => array(
        'home' => array(
            'methods' => array(
                'get' => 0,
            ),
            'dispatch' => array(
                'controller' => 'home',
                'action' => 'index'
            )
        ),
        'get_packages_for_dropdown' => array(
            'methods' => array(
                'get' => 0,
            ),
            'dispatch' => array(
                'controller' => 'packages',
                'action' => 'getKeyValuePackages'
            )
        ),
        'get_countries_dropdown' => array(
            'methods' => array(
                'get' => 0
            ),
            'dispatch' => array(
                'controller' => 'countries',
                'action' => 'keyValueForDropdown'
            )
        ),
        'map_offers' => array(
            'methods' => array(
                'get' => 0 # additional methods might be added, if allowed, but, better use resources for REST.
            ),
            'dispatch' => array(
                'controller' => 'offers',
                'action' => 'mapOffers'
            )
        ),
        'generate_packages_files' => array(
            'methods' => array(
                'get' => 1
            ),
            'dispatch' => array(
                'controller' => 'packages',
                'action' => 'generatePackagesFiles'
            )
        ),
        'package_offers' => array(
            'methods' => array(
                'get' => 2
            ),
            'dispatch' => array(
                'controller' => 'packages',
                'action' => 'show_package_offers'
            )
        ), ## package with ID&COUNTRY must show all packages offers.
    )
);