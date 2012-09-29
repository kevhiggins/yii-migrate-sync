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
		
		foreach($table->indexes as $index)
			$output .= "\t\t\t'{$index->SQL}',\n";
		
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
	
	public function createAddColumnMigration($table, $column)
	{
		return "\t\t\$this->addColumn('{$table->name}', '{$column->name}', '{$column->type}');\n";
	}
	
	public function createDropColumnMigration($table, $column)
	{
		return "\t\t\$this->dropColumn('{$table->name}', '{$column->name}');\n";
	}
	
	public function createAlterColumnMigration($table, $column)
	{
		return "\t\t\$this->alterColumn('{$table->name}', '{$column->name}', '$column->type');\n";
	}
	
	public function createCreateIndexMigration($table, $name, $index)
	{
		if($index->isPrimary)
			return "\t\t\$this->execute('{$index->SQL}');\n";
		else
			return "\t\t\$this->createIndex('$name', '{$table->name}', '{$index->formattedColumns}', {$index->isUnique});\n";
	}
	
	public function createNewTableMigrations()
	{
		$output = '';
		$currentTables = $this->getDbTables();
		$previousTables = $this->getTmpDbTables();
		
		foreach($this->getNewKeys($currentTables, $previousTables) as $table)
			$output .= $this->createCreateTableMigration($table)."\n";

		foreach($this->getOldKeys($currentTables, $previousTables) as $table)
			$output .= $this->createDropTableMigration($table)."\n";
		
		foreach($this->getCurrentKeys($currentTables, $previousTables) as $table)
			$output .= $this->createTableDiffMigration(
				$currentTables[$table->name],
				$previousTables[$table->name]			
			)."\n";
		
		
		// Write constraints
		
		return $output;
	}
	
	public function getTableDiffMigration($table, $tmpTable)
	{
		$output .= '';

		// Create column migration
		$output .= $this->getColumnDiffMigration($table, $tmpTable);
		
		// Create index migration
		
		// Create foreign key migration
		
		return $output;
	}

	/**
	 * Create new columns, delete old columns, and alter changed columns.
	 * @param EDbTableSchema $table
	 * @param EDbTableSchema $tmpTable
	 */
	public function getColumnDiffMigration($table, $tmpTable)
	{
		$output = '';
		
		$currentColumns = $table->columns;
		$prevColumns = $tmpTable->columns;
		
		// Create new columns
		foreach($this->getNewKeys($currentColumns, $prevColumns) as $column)
			$output .= $this->createAddColumnMigration($table, $column);
		
		// Delete old columns
		foreach($this->getOldKeys($currentColumns, $prevColumns) as $column)
			$output .= $this->createDropColumnMigration($table, $column);
		
		// Find modified columns and alter them
		foreach($this->getCurrentKeys($currentColumns, $prevColumns) as $column)
		{
			$currentColumn = $table->columns[$column->name]->getType();
			$previousColumn = $tmpTable->columns[$column->name]->getType();
			
			if($currentColumn->getType() !== $previousColumn->getType())
				$output .= $this->createAlterColumnMigration($table, $currentColumn);			
		}		
		
		return $output;
	}
	
	public function getIndexDiffMigration($table, $tmpTable)
	{
		$output .= '';
		$currentIndexes = $table->indexes;
		$prevIndexes = $table->indexes;
		
		foreach($this->getNewKeys($currentIndexes, $prevIndexes) as $name=>$index)
			$output .= $this->createCreateIndexMigration($table, $name, $index);
		
	}
	
	public function geetForeignKeyDiffMigration($table, $tmpTable)
	{
		
	}
	
	public function createDropTableMigrations()
	{
		// Find new Tables and make them
		$output = '';
		
		$currentTables = $this->getDbTables();
		$previousTables = $this->getTmpDbTables();		
		foreach($this->getNewKeys($currentTables, $previousTables) as $table)
			$output .= $this->createDropTableMigration($table)."\n";		
		
		foreach($this->getOldKeys($currentTables, $previousTables) as $table)
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
	
	public function getNewKeys($current, $previous)
	{
		return array_diff_key($current, $previous);		
	}
	
	public function getOldKeys($current, $previous)
	{
		return array_diff_key($previous, $current);
	}
	
	public function getCurrentKeys($current, $previous)
	{
		return array_intersect_key($current, $previous);
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