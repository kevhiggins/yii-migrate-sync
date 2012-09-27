<?php
class EMysqlSchema extends CMysqlSchema
{
	/**
	 * Loads the metadata for the specified table.
	 * @param string $name table name
	 * @return EMysqlTableSchema driver dependent table metadata. Null if the table does not exist.
	 */
	protected function loadTable($name)
	{
		$table=new EMysqlTableSchema;
		$this->resolveTableNames($table,$name);
	
		if($this->findColumns($table))
		{
			$this->findConstraints($table);
			return $table;
		}
		else
			return null;
	}
	
	/**
	 * Creates a table column.
	 * @param array $column column metadata
	 * @return CDbColumnSchema normalized column metadata
	 */
	protected function createColumn($column)
	{
		$c=new EMysqlColumnSchema;
		$c->name=$column['Field'];
		$c->rawName=$this->quoteColumnName($c->name);
		$c->allowNull=$column['Null']==='YES';
		$c->isPrimaryKey=strpos($column['Key'],'PRI')!==false;
		$c->isForeignKey=false;
		$c->init($column['Type'],$column['Default']);
		$c->autoIncrement=strpos(strtolower($column['Extra']),'auto_increment')!==false;
	
		return $c;
	}
	
	/**
	 * Collects the foreign key column details for the given table.
	 * @param CMysqlTableSchema $table the table metadata
	 */
	protected function findConstraints($table)
	{
		$row=$this->getDbConnection()->createCommand('SHOW CREATE TABLE '.$table->rawName)->queryRow();
		$matches=array();
		$regexp='/CONSTRAINT\s+([^\(^\s]+)\s+FOREIGN KEY\s+\(([^\)]+)\)\s+REFERENCES\s+([^\(^\s]+)\s*\(([^\)]+)\)([^,\n]*)/mi';
		foreach($row as $sql)
		{
			if(preg_match_all($regexp,$sql,$matches,PREG_SET_ORDER))
				break;
		}
		foreach($matches as $match)
		{
			$types = array('name'=>str_replace('`','', $match[1]));
			if(isset($match[5]))
				$types += $this->findReferenceOptions($match[5]);
						
			$keys=array_map('trim',explode(',',str_replace('`','',$match[2])));
			$fks=array_map('trim',explode(',',str_replace('`','',$match[4])));
			foreach($keys as $k=>$name)
			{
				$table->foreignKeys[$name]=array(str_replace('`','',$match[3]),$fks[$k]);
				if(isset($types))
					$table->referenceOptions[$name] = $types; 
				if(isset($table->columns[$name]))
					$table->columns[$name]->isForeignKey=true;
			}
		}
		
		$this->findIndexes($table, $row);
		$this->findEngine($table, $row);
	}
	
	protected function findIndexes($table, $row)
	{
		// Extract indexes
		$keyRegexp = '/(UNIQUE |PRIMARY |FOREIGN |)KEY\s+([^\(^\s]+)?\s*\((.*)\)/mi';
		$matches = array();
		foreach($row as $sql)
		{
			if(preg_match_all($keyRegexp,$sql,$matches,PREG_SET_ORDER))
				break;
		}
		foreach($matches as $match)
		{
			if($match[1] === 'FOREIGN ')
				continue;
			$isUnique = false;
			if($match[1] === 'UNIQUE ' || $match[1] === 'PRIMARY ')
			{
				$isUnique = true;
			}
			$cols=array_map('trim',explode(',',str_replace('`','',$match[3])));
			$indexName = $match[1] === 'PRIMARY ' ? 'PRIMARY' : str_replace('`', '', $match[2]);
			
			$table->indexes[$indexName] = array('columns'=>$cols, 'isUnique'=>$isUnique);
		}		
	}
	
	protected function findEngine($table, $row)
	{
		$engineRegexp = '/ENGINE=([^\s]+)/mi';
		$matches = array();
		foreach($row as $sql)
		{
			if(preg_match($engineRegexp,$sql,$matches))
				break;
		}
		if(isset($matches[1]))
			$table->engine = $matches[1];		
	}
	
	protected function findReferenceOptions($data)
	{
		if(isset($data))
		{
			$types = array();
			$ons = explode(' ON ', ' '.$data);
			if(isset($ons[0]) && $ons[0] === '')
				array_shift($ons);
			foreach($ons as $on)
			{
				$pieces = explode(' ', $on);
				$type = array_shift($pieces);
				$types[$type] = implode(' ', $pieces);
			}
			if(!isset($types['DELETE']))
				$types['DELETE'] = null;
			if(!isset($types['UPDATE']))
				$types['UPDATE'] = null;
			return $types;
		}		
		return null;
	}
	
	public function generateForeignKey($name, $columns, $refTable, $refColumns, $delete=null, $update=null)
	{
		$columns=preg_split('/\s*,\s*/',$columns,-1,PREG_SPLIT_NO_EMPTY);
		foreach($columns as $i=>$col)
			$columns[$i]=$this->quoteColumnName($col);
		$refColumns=preg_split('/\s*,\s*/',$refColumns,-1,PREG_SPLIT_NO_EMPTY);
		foreach($refColumns as $i=>$col)
			$refColumns[$i]=$this->quoteColumnName($col);
		$sql='CONSTRAINT '.$this->quoteColumnName($name)
		.' FOREIGN KEY ('.implode(', ', $columns).')'
		.' REFERENCES '.$this->quoteTableName($refTable)
		.' ('.implode(', ', $refColumns).')';
		if($delete!==null)
			$sql.=' ON DELETE '.$delete;
		if($update!==null)
			$sql.=' ON UPDATE '.$update;
		return $sql;
	}	
	
	public function beforeMigrationOutput()
	{
		return '$this->getDbConnection()->schema->checkIntegrity(false);';
	}
	
	public function afterMigrationOutput()
	{
		return '$this->getDbConnection()->schema->checkIntegrity(true);';
	}	
}