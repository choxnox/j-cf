<?php

return array(
    'appnamespace' => 'Application',
    'autoloaderNamespaces' => array(
        'Library_'
    ),
    'bootstrap' => array(
        'class' => 'Bootstrap',
        'path' => APPLICATION_PATH . '/Bootstrap.php'
    ),
    'includePaths' => array(
        'application' => APPLICATION_PATH,
        'library' => APPLICATION_PATH . '/..'
    ),
    'phpSettings' => array(
        'display_startup_errors' => 0,
        'display_errors' => 0
    ),
    'resources' => array(
        'db' => array(
            'adapter' => 'MYSQLi',
            'isDefaultTableAdapter' => true,
            'params' => array(
                'host' => 'localhost',
                'dbname' => 'jobs_currencyfair_dashboard',
                'username' => 'currencyfair_dsh',
                'password' => 'zdxMV3YSCcLBsHKB',
                'charset' => 'utf8',
                'driver_options' => array(
                    '1002' => 'SET NAMES utf8'
                ),
                'profiler' => false
            )
        ),
        'frontController' => array(
            'defaultModule' => 'default',
            'moduleDirectory' => APPLICATION_PATH . '/Modules',
            'params' => array(
                'displayExceptions' => true,
                'prefixDefaultModule' => false
            )
        ),
        'layout' => array(
            'layoutPath' => APPLICATION_PATH . '/Views/Layouts/'
        ),		
        'modules' => array(),
        'session' => array(
            'use_cookies' => true,
            'name' => 'currencyfair_dashboard',
            'use_only_cookies' => true,
            'save_path' => APPLICATION_PATH . '/../sessions'
        ),
		'view' => array()
    )
);