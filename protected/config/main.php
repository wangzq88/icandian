<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'icandian.com',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'111111',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				// a standard rule mapping '/' to 'site/index' action
    			'' => 'site/index',		
				'index' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'index_<page:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'index_cuisine_<cuisine:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'index_cuisine_<cuisine:\d+>_<page:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'area_<area:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'area_<area:\d+>_<page:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'area_<area:\d+>_cuisine_<cuisine:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'area_<area:\d+>_cuisine_<cuisine:\d+>_<page:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'section_<section:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'section_<section:\d+>_<page:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'section_<section:\d+>_cuisine_<cuisine:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'section_<section:\d+>_cuisine_<cuisine:\d+>_<page:\d+>' => array('site/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'shop_<id:\d+>' => array('shop/index', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
				'shop_comment_<id:\d+>' => array('shopComment/index', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
				'shop_comment_<id:\d+>_<page:\d+>' => array('shopComment/index', 'urlSuffix'=>'.html', 'caseSensitive'=>false),
				'feedback' => array('feedback/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'feedback_<page:\d+>' => array('feedback/index','urlSuffix'=>'.html','caseSensitive'=>false),
				'login' => array('site/login','urlSuffix'=>'.html','caseSensitive'=>false),
				'contact' => array('site/contact','urlSuffix'=>'.html','caseSensitive'=>false),
			    // a standard rule to handle 'post/update' and so on
			    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',		
			),
		),
		
/*		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=idingcan',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'idingcan_',
			'schemaCachingDuration' => 180,
			'enableProfiling'=>true,
			'enableParamLogging' => true,			
		),
		'cache' => array(
			'class' => 'CFileCache',
			'directoryLevel' => 1,
		),		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CProfileLogRoute',
				),			
				/*
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),*/
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);