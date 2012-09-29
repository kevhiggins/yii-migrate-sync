<?php
//Yii::import('system.cli.commands.MigrateCommand');
//Yii::import('application.components.db.schema.EDbMigrationBuilder');
//Yii::import('application.components.db.schema.mysql.*');

class EMigrateCommand extends MigrateCommand
{
	public $db;
	public $syncDb;

	public $syncConnectionId = 'syncDb';

	protected $_schema;
	protected $_migrations;

	public function actionSync($args)
	{
		//$db = $this->getDbConnection();
		//CVarDumper::dump($db->schema->getTables());
		$this->checkTables();

		// Foreach sync table
			// If does not exist in dbTables
				// Add delete table migration



		// Foreach table
		// Create all new tables

		// Delete all deleted tables

		// Forech

		//$tables = Yii::app()->db->schema->getTables();

	}

	protected function checkTables()
	{
		$db = $this->getDbConnection();
		$syncDb = $this->getSyncDbConnection();

		$this->_schema = $db->schema;

		//CVarDumper::dump($db->schema->getTables());
	//	exit;

		$dbTables = $db->schema->getTables();
	//	$syncDbTables = $syncDb->schema->getTables();
	//	CVarDumper::dump($this->addTableMigration($dbTables['test']));
//		$this->_migrations['name'] = 'm120922_203026_woo';
//		$this->_migrations['create'][] = $dbTables['test']->migrationCreate($db->schema);
//		$this->_migrations['drop'][] = $dbTables['test']->migrationDrop($db->schema);


		$builder = new EDbMigrationBuilder($this, $db, $syncDb);
		$path  = __DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'migration.php';
		$name = $builder->name;

		//$filename = "C:\wamp\www\yii-migrate-sync\protected\migrations\";

		$filename = 'C:\wamp\www\yii-migrate-sync\protected\migrations\\'.$builder->name.'.php';
		$this->writeTestFile($filename, $this->renderFile($path, array('builder'=>$builder), true));

		//CVarDumper::dump($dbTables['test']->generateSQL($db->schema));
		exit;

		// Find New Tables
		$newTables = array_diff_key($dbTables, $syncDbTables);
		foreach($newTables as $newTable)
			$this->addTableMigration($newTable);

		// Find Deleted Tables
		$deletedTables = array_diff_key($syncDbTables, $dbTables);
		foreach($deletedTables as $deletedTable)
		{
			$this->deleteTableMigration($deletedTable);
		}

		// Find Shared Tables
		$sharedTables = array_intersect_key($dbTables, $syncDbTables);
		foreach($sharedTables as $sharedTable)
		{
			$this->checkColumns($sharedTable);
		}

	//	CVarDumper::dump($deletedTables);
	}

	public function getMigrationName()
	{
		return 'm'.gmdate('ymd_His').'_'.'migration_sync';
	}

	protected function checkColumns($table)
	{

	}



	protected function deleteTableMigration()
	{

	}

	protected function createColumnMigration()
	{

	}

	protected function alterColumnMigration()
	{

	}

	// Check indexes

	// Check keys

	private $_db;
	protected function getDbConnection()
	{
		if($this->_db!==null)
			return $this->_db;
		else if(($this->_db=Yii::createComponent($this->db)) instanceOf CDbConnection)
			return $this->_db;

		echo "Error: db config invalid.";
		//echo "Error: CMigrationCommand.connectionID '{$this->connectionID}' is invalid. Please make sure it refers to the ID of a CDbConnection application component.\n";
		exit(1);
	}

	/**
	 * @var CDbConnection
	 */
	private $_syncDb;
	protected function getSyncDbConnection()
	{
		if($this->_syncDb!==null)
			return $this->_syncDb;
		else if(($this->_syncDb=Yii::createComponent($this->syncDb)) instanceof CDbConnection)
			return $this->_syncDb;

		echo "Error: syncDb config invalid.";
		exit(1);
	}

	protected function writeTestFile($filename, $data)
	{
		file_put_contents($filename, $data);
	}

}