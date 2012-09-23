<?php
class EMysqlTableSchema extends CMysqlTableSchema
{
	public $referenceOptions = array();
	public $indexes = array();
	public $engine;
	
	public function generateSQL($schema)
	{
		$output = $this->generateIndexes($schema);
		$output .= $this->generateForeignKeys($schema);
		return $output;
	}
	
	protected function generateIndexes($schema)
	{
		$output = '';
		foreach($this->indexes as $name=>$index)
		{
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
			$output .= ",\n";
		}
		return $output;		
	}
	
	protected function generateForeignKeys($schema)
	{	
		$output = '';
		foreach($this->foreignKeys as $column=>$foreignKey)
		{
			$refOptions = $this->referenceOptions[$column];
			$output .= $schema->generateForeignKey($refOptions['name'], $column, $foreignKey[0], $foreignKey[1], $refOptions['DELETE'], $refOptions['UPDATE'])."\n";
		}
		return $output;
	}
	

}