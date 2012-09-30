<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	'import'=>array(
		'application.components.db.schema.*',
		'application.components.db.schema.mysql.*',
		'system.cli.commands.MigrateCommand',
	),

	// application components
	'components'=>array(
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=migration-sync',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
//			'driverMap' => array(
//				'mysql' => 'EMysqlSchema',
//			),
		),
		'db1'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=test1',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'driverMap' => array(
				'mysql' => 'EMysqlSchema',
			),
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
		'emigrate'=>array(
			'class'=>'application.commands.EMigrateCommand',
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
			'syncDb'=>array(
				'class'=>'CDbConnection',
				'connectionString' => 'mysql:host=localhost;dbname=migration-sync',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8',
				'driverMap' => array(
					'mysql' => 'EMysqlSchema',
				),
			),
		),
		'testmigrate'=>array(
			'class'=>'system.cli.commands.MigrateCommand',
			'connectionID'=>'db1',
			'interactive'=>false,
			'migrationPath'=>'application.tests.migrations.db1',
		)
	),
);