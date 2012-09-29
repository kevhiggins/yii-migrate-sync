<?php
class EMysqlIndexSchema extends CComponent
{
	public $name;
	public $isUnique;
	public $columns;
	
	public function getSQL()
	{
		$output = '';
		if($this->isPrimary)
		{
			$output .= 'PRIMARY KEY';
		}
		else
		{
			if($this->isUnique)
				$output .= 'UNIQUE ';
			$output .= 'KEY '.CMysqlSchema::quoteSimpleColumnName($this->name);
		}
		$output .= ' ('.implode(',', array_map('CMysqlSchema::quoteSimpleColumnName', $this->columns)).')';
		return $output;
	}
	
	public function getIsPrimary()
	{
		return $this->name === 'PRIMARY';
	}
	
	public function getFormattedColumns()
	{
		return implode(',', $this->columns);
	}
}