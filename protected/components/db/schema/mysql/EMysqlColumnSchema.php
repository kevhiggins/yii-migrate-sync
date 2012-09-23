<?php
class EMysqlColumnSchema extends CMysqlColumnSchema
{
	protected function generateSQL()
	{
		$output = $this->dbType;
		if(!$this->allowNull)
			$output .= ' NOT NULL';
		if($this->defaultValue !== null)
			$output .= " DEFAULT '{$this->defaultValue}'";
		else if($this->allowNull)
			$output .= ' DEFAULT NULL';
		if($this->autoIncrement)
			$output .= ' AUTOINCREMENT';
		return $output;
	}	
}