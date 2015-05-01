<?php

return array(
	'routes' => array(
		'app' => array(
			'route' => '',
			'defaults' => array(
				'module' => 'default',
				'controller' => 'index',
				'action' => 'index'
			)
		),
		'auth-login' => array(
			'route' => 'login',
			'defaults' => array(
				'module' => 'default',
				'controller' => 'auth',
				'action' => 'login'
			)
		),
		'auth-logout' => array(
			'route' => 'logout',
			'defaults' => array(
				'module' => 'default',
				'controller' => 'auth',
				'action' => 'logout'
			)
		)
	)
);