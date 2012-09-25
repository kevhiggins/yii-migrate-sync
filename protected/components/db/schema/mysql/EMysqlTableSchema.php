<?php
class EMysqlTableSchema extends CMysqlTableSchema
{
	public $referenceOptions = array();
	public $indexes = array();
	public $engine;
	
	public function migrationCreate($schema)
	{
		$output = "\t\t\$this->createTable('{$this->name}', array(\n";
		foreach($this->columns as $column)
			$output .= "\t\t\t'{$column->name}' => \"".$column->generateSQL()."\",\n";
		
		foreach($this->generateIndexes($schema) as $index)
			$output .= "\t\t\t'$index',\n";
		
		$output = rtrim($output, "\n,");
		
		$output .= "\n\t\t),\n";
		$output .= "\t\t'".$this->generateOptions()."'";
		$output .= ");\n";
		return $output;
	}
	
	public function migrationDrop($schema)
	{
		return "\t\t\$this->dropTable('{$this->name}');\n";
	}
	
	public function migrationDelete()
	{
		
	}
	
	public function generateSQL($schema)
	{
		$output = $this->generateIndexes($schema);
	//	$output .= $this->generateForeignKeys($schema);
		
		return rtrim($output, "\n,");
	}
	
	public function generateOptions()
	{
		$output = '';
		if(isset($this->engine))
			$output .= "ENGINE={$this->engine}";
		return $output;		
	}
	
	
	public function generateIndexes($schema)
	{
		$indexes = array();
		foreach($this->indexes as $name=>$index)
		{
			$output = '';
			if($name === 'PRIMARY')
			{
				$output .= 'PRIMARY KEY';
			}
			else
			{
				if($index['isUnique'])
					$output .= 'UNIQUE ';
				$output .= 'KEY '.$schema->quoteSimpleColumnName($name);
			}
			$output .= ' ('.implode(',', array_map('CMysqlSchema::quoteSimpleColumnName', $index['columns'])).')';
			$indexes[$name] = $output;
		}
		return $indexes;		
	}
	
	protected function generateForeignKeys($schema)
	{	
		$output = '';
		foreach($this->foreignKeys as $column=>$foreignKey)
		{
			$refOptions = $this->referenceOptions[$column];
			$output .= $schema->generateForeignKey($refOptions['name'], $column, $foreignKey[0], $foreignKey[1], $refOptions['DELETE'], $refOptions['UPDATE']).",\n";
		}
		return $output;
	}
	

}