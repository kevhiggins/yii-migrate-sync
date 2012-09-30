<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/console.php'),
	array(
		'components'=>array(
			/*
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			*/
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
			'db2'=>array(
				'class'=>'CDbConnection',
				'connectionString' => 'mysql:host=localhost;dbname=test2',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8',
				'driverMap' => array(
					'mysql' => 'EMysqlSchema',
				),
			),
		),
		'commandMap'=>array(
			'migrate1'=>array(
				'class'=>'system.cli.commands.MigrateCommand',
				'connectionID'=>'db1',
				'interactive'=>false,
				'migrationPath'=>'application.tests.migrations.db1',
			),
			'migrate2'=>array(
				'class'=>'system.cli.commands.MigrateCommand',
				'connectionID'=>'db2',
				'interactive'=>false,
				'migrationPath'=>'application.tests.migrations.db2',
			),
			'emigrate'=>array(
				'migrationPath'=>'application.tests.migrations.db2',
				'syncTemplateFile'=>'test_migration.php',
			),
		),
	)
);
