<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
		// uncomment the following to use a MySQL database
/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=migration-diff',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
//			'driverMap' => array(
//				'mysql' => 'EMysqlSchema',
//			),
		),
		*/
		'syncDb'=>array(
				'class'=>'CDbConnection',
				'connectionString' => 'mysql:host=localhost;dbname=migration-sync',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
	'commandMap'=>array(
		'migrate'=>array(
			'class'=>'application.commands.MigrateExtendedCommand',
			'db'=>array(
					'class'=>'CDbConnection',
					'connectionString' => 'mysql:host=localhost;dbname=migration-diff',
					'emulatePrepare' => true,
					'username' => 'root',
					'password' => '',
					'charset' => 'utf8',
					'driverMap' => array(
						'mysql' => 'EMysqlSchema',
					),
			),				
		),
	),
);