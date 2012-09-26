<?php
class EDbMigrationBuilder extends CComponent
{
	protected $_schema;
	protected $_db;
	protected $_tmpDb;
	
	public function __construct($db, $tmpDb)
	{
		$this->_db = $db;
		$this->_tmpDb = $tmpDb;
		$this->_schema = $db->schema;
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
	
	public function createCreateTablesMigration()
	{
		$output = '';
		foreach($this->_schema->getTables() as $table)
			$output .= $this->createCreateTableMigration($table)."\n";
		return $output;
	}
	
	public function createDropTablesMigration()
	{
		$output = '';
		foreach($this->_schema->getTables() as $table)
			$output .= $this->createDropTableMigration($table)."\n";
		return $output;
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