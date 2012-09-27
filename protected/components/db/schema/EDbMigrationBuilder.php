<?php
class EDbMigrationBuilder extends CComponent
{
	protected $_schema;
	protected $_db;
	protected $_tmpDb;
	protected $_command;
	protected $_name;
	
	public function __construct($command, $db, $tmpDb)
	{
		$this->_db = $db;
		$this->_tmpDb = $tmpDb;
		$this->_schema = $db->schema;
		$this->_command = $command;
		$this->_name = $command->getMigrationName();
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	public function createCreateTableMigration($table)
	{
		$output = "\t\t\$this->createTable('{$table->name}', array(\n";
		foreach($table->columns as $column)
			$output .= "\t\t\t'{$column->name}' => \"".$column->generateSQL()."\",\n";
		
		foreach($table->generateIndexes($this->_schema) as $index)
			$output .= "\t\t\t'$index',\n";
		
		$output = rtrim($output, "\n,");
		
		$output .= "\n\t\t),\n";
		$output .= "\t\t'".$table->generateOptions()."'";
		$output .= ");\n";
		return $output;		
	}
	
	public function createDropTableMigration($table)
	{
		return "\t\t\$this->dropTable('{$table->name}');\n";
	}
	
	public function createAlterTableMigration($table)
	{
		return $this->getTableDiffMigration(
			$this->_db->schema[$table->name],
			$this->_tmpDb->schema[$table->name]
		);
	}
	
	public function createAddColumnMigration($table, $column)
	{
		return "\t\t\$this->addColumn('{$table->name}', '{$column->name}', '{$column->type}');";
	}
	
	public function createDropColumnMigration($table, $column)
	{
		return "\t\t\$this->dropColumn('{$table->name}', '{$column->name}');";
	}
	
	//public function
	
	public function getTableDiffMigration($table, $tmpTable)
	{
		$output .= '';
		// Create new columns
		foreach($this->getNewColumns($table, $tmpTable) as $column)
			$output .= $this->createAddColumnMigration($table, $column);
		
		// Delete old columns
		foreach($this->getOldColumns($table, $tmpTable) as $column)
			$output .= $this->createDropColumnMigration($table, $column);
		
		// Find modified columns and alter them
		foreach($this->getCurrentColumns($table, $tmpTable) as $column)
			$output .= $this->getColumnDiffMigration(
				$table->columns[$column->name],
				$tmpTable->columns[$column->name]
			); 
	}
	
	// TODO THIS
	public function getColumnDiffMigration($column, $tmpColumn)
	{
		
	}
	
	public function createNewTableMigrations()
	{
		$output = '';
		foreach($this->getNewTables() as $table)
			$output .= $this->createCreateTableMigration($table)."\n";

		foreach($this->getOldTables() as $table)
			$output .= $this->createDropTableMigration($table)."\n";
		
		foreach($this->getCurrentTables() as $table)
			$output .= $this->createAlterTableMigrations($table)."\n";
		
		
		// Find old tables and delete them
		
		
		// Find modified tables and adjust them
		
		// Write constraints
		
		return $output;
	}
	
	public function createDropTableMigrations()
	{
		// Find new Tables and make them
		$output = '';
		foreach($this->getNewTables() as $table)
			$output .= $this->createDropTableMigration($table)."\n";		
		
		foreach($this->getOldTables() as $table)
			$output .= $this->createCreateTableMigration($table)."\n";		
		

		return $output;
	}
	
	public function getDbTables()
	{
		$tables = $this->_db->schema->getTables();
		if(isset($tables[$this->_command->migrationTable]))
			unset($tables[$this->_command->migrationTable]);
		return $tables;
	}
	
	public function getTmpDbTables()
	{
		$tables = $this->_tmpDb->schema->getTables();
		if(isset($tables[$this->_command->migrationTable]))
			unset($tables[$this->_command->migrationTable]);
		return $tables;		
	}
	
	public function getNewTables()
	{
		return array_diff_key(
			$this->getDbTables(),
			$this->getTmpDbTables()
		);
	}
	
	public function getOldTables()
	{
		return array_diff_key(
			$this->getTmpDbTables(),
			$this->getDbTables()
		);
	}
	
	public function getCurrentTables()
	{
		return array_intersect_key(
			$this->getDbTables(),
			$this->getTmpDbTables()
		);
	}
	
	public function getNewColumns($table,  $tmpTable)
	{
		return array_diff_key(
			$table->columns,
			$tmpTable->columns
		);	
	}
	
	public function getOldColumns($table, $tmpTable)
	{
		return array_diff_key(
			$tmpTable->columns,
			$table->columns
		);	
	}
	
	public function getCurrentColumns($table, $tmpTable)
	{
		return array_intersect_key(
			$table->columns,
			$tmpTable->columns
		);
	}	
	
	public function beforeMigrationOutput()
	{
		return "\t\t".$this->_schema->beforeMigrationOutput()."\n\n";
	}
	
	public function afterMigrationOutput()
	{
		return "\t\t".$this->_schema->afterMigrationOutput()."\n\n";
	}	
}