<?php
Yii::import('system.cli.commands.MigrateCommand');
//Yii::import('application.components.Controller');\
Yii::import('application.components.db.schema.mysql.EMysqlSchema');
Yii::import('application.components.db.schema.mysql.EMysqlTableSchema');
Yii::import('application.components.db.schema.mysql.EMysqlColumnSchema');

class MigrateExtendedCommand extends MigrateCommand
{
	public $db;
	public $syncDb;
	
	public $syncConnectionId = 'syncDb';
	
	public function actionSync($args)
	{
		$db = $this->getDbConnection();
		CVarDumper::dump($db->schema->getTables());
		//$this->checkTables();
		
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
	//	$syncDb = $this->getSyncDbConnection();
		
		CVarDumper::dump($db->schema->getTables());
		exit;
		
		$dbTables = $db->schema->getTables();
		$syncDbTables = $syncDb->schema->getTables();
		
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
	
	protected function checkColumns($table)
	{
		
	}
	
	protected function addTableMigration($table)
	{
		$output = "\$this->createTable('{$table->name}', array(\n";
		foreach($table->columns as $column)
		{
			$sql = $this->generateColumnSQL($column);
			$output .= "'{$column->name}' => '$sql',";
		}		 
		$output .= ");";
	}
	
	protected function generateColumnSQL($column)
	{
		$output = $column->dbType;
		if(!$column->allowNull)
			$output .= ' NOT NULL';
		if($column->defaultValue !== null)
			$output .= " DEFAULT '{$column->defaultValue}'";
		else if($column->allowNull)
			$output .= ' DEFAULT NULL';
		if($column->autoIncrement)
			$output .= ' AUTOINCREMENT';
		return $output;
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
	
}