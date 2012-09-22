<?php
Yii::import('system.cli.commands.MigrateCommand');

class MigrateExtendedCommand extends MigrateCommand
{
	public $syncConnectionId = 'syncDb';
	
	public function actionSync($args)
	{
		$db = $this->getDbConnection();
		$syncDb = $this->getSyncDbConnection();
		
		$dbTables = $db->schema->getTables();
		$syncDbTables = $syncDb->schema->getTables();
		
		//$tables = Yii::app()->db->schema->getTables();
		CVarDumper::dump();	
	}
	
	/**
	 * @var CDbConnection
	 */
	private $_syncDb;
	protected function getSyncDbConnection()
	{
		if($this->_syncDb!==null)
			return $this->_syncDb;
		else if(($this->_syncDb=Yii::app()->getComponent($this->syncConnectionId)) instanceof CDbConnection)
			return $this->_syncDb;
	
		echo "Error: CMigrationCommand.connectionID '{$this->connectionID}' is invalid. Please make sure it refers to the ID of a CDbConnection application component.\n";
		exit(1);
	}
}