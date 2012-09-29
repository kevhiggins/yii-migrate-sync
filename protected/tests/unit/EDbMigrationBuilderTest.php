<?php
class EDbMigrationBuilderTest extends CDbTestCase
{
	protected $_db1;
	protected $_db2;
	protected $_builder;

	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		$this->_db1 = Yii::app()->getComponent('db1');
		$this->_db2 = Yii::app()->getComponent('db2');

		$this->_builder = new EDbMigrationBuilder($this->_db1, $this->_db2);

		$this->resetDatabases();

		Yii::app()->getCommandRunner()->run(array('entry', 'migrate1', 'up'));
	}

	protected function resetDatabases()
	{
		$this->resetDatabase($this->_db1);
		$this->resetDatabase($this->_db2);
	}

	protected function resetDatabase($db)
	{
		$db->schema->dropTables();
		$db->schema->refresh();
	}

	protected function setUp()
	{
		parent::setUp();
	}

	public function testCreateCreateTableMigration()
	{
		$output = $this->_builder->createCreateTableMigration($this->_db1->schema->tables['test']);
		var_dump($output);
		$this->assertTrue(true);
	}
}