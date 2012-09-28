<?php
class EMysqlTableSchema extends CMysqlTableSchema
{
	public $referenceOptions = array();
	public $indexes = array();
	public $engine;
	
	public function generateOptions()
	{
		$output = '';
		if(isset($this->engine))
			$output .= "ENGINE={$this->engine}";
		return $output;		
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