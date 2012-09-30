<?php
class EDbMigrationBuilderTest extends CDbTestCase
{
	protected $_db1;
	protected $_db2;
	protected $_builder;
	/**
	 *
	 * @var MigrateCommand
	 */
	protected $_migrate1;
	protected $_migrate2;

	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		$this->_db1 = Yii::app()->getComponent('db1');
		$this->_db2 = Yii::app()->getComponent('db2');

		$runner = Yii::app()->getCommandRunner();
		$this->_builder = new EDbMigrationBuilder($this->_db1, $this->_db2, $runner->createCommand('emigrate'));
		$this->_migrate1 = $runner->createCommand('migrate1');
		$this->_migrate2 = $runner->createCommand('migrate2');

		$this->resetDatabases();

//		Yii::app()->getCommandRunner()->run(array('entry', 'migrate1', 'up'));
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
		$this->_migrate1->run(array('up'));
		$this->_db1->schema->refresh();

		$output = $this->_builder->createCreateTableMigration($this->_db1->schema->tables['test']);
		$this->_builder->writeTestMigration($output, '');

		$this->_migrate2->run(array('up'));
		$this->_db2->schema->refresh();

		unlink($this->_builder->getMigrationPath());

		$this->assertTrue($this->_db1->schema->equals($this->_db2->schema));
	}
}