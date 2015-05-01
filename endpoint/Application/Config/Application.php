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
                'dbname' => 'jobs_currencyfair_endpoint',
                'username' => 'currencyfair_api',
                'password' => 'WER7rmYLZY7RWpvF',
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
        'modules' => array(),
		'router' => array(
			'routes' => array(
				'rest' => array(
					'type' => "Zend_Rest_Route"
				)
			)
		)
    )
);