<?php
Yii::import('system.cli.commands.MigrateCommand');

class MigrateExtendedCommand extends MigrateCommand
{
	public $syncConnectionId = 'syncDb';
	
	public function actionSync($args)
	{
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
	
	
	/**
	 * @var CDbConnection
	 */
	private $_syncDb;
	protected function getSyncDbConnection()
	{
		if($this->_syncDb!==null)
			return $this->_syncDb;
		else if(($this->_syncDb=Yii::app()->getComponent($this->syncConnectionId)) instanceof CDbConnection)
			return $this->_syncDb;
	
		echo "Error: CMigrationCommand.connectionID '{$this->connectionID}' is invalid. Please make sure it refers to the ID of a CDbConnection application component.\n";
		exit(1);
	}
}