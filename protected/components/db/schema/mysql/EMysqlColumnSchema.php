<?php
class EMysqlColumnSchema extends CMysqlColumnSchema
{
	public function generateSQL()
	{
		$output = $this->dbType;
		if(!$this->allowNull)
			$output .= ' NOT NULL';
		if($this->defaultValue !== null)
			$output .= " DEFAULT '{$this->defaultValue}'";
		else if($this->allowNull)
			$output .= ' DEFAULT NULL';
		if($this->autoIncrement)
			$output .= ' AUTO_INCREMENT';
		return $output;
	}

	public function getDefinition()
	{
		return $this->generateSQL();
	}
}