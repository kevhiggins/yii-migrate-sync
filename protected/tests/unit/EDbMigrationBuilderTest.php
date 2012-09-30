<?php
class EDbMigrationBuilderTest extends CDbTestCase
{
	/**
	 *
	 * @var CDbConnection
	 */
	protected $_db1;
	/**
	 *
	 * @var CDbConnection
	 */
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
		$this->resetDatabases();
	}

	/*
	public function testCreateCreateTableMigration()
	{
		$this->createBaseTable($this->_db1);
		$this->syncDb2($this->_builder->createCreateTableMigration($this->_db1->schema->tables['test']));
		$this->assertTrue($this->_db1->schema->equals($this->_db2->schema));
	}
*/
	public function testCreateAddColumnMigration()
	{
		$this->createBaseTable($this->_db1);
		$this->createBaseTable($this->_db2);

		$this->_db1->createCommand()->addColumn('test', 'testcolumn', "varchar(64) NOT NULL DEFAULT 'test2'");

		$output = $this->_builder->createAddColumnMigration(
				$this->_db1->schema->tables['test'],
				$this->_db1->schema->tables['test']->columns['testcolumn']
		);
		$this->syncDb2($output);
		$this->assertTrue($this->_db1->schema->equals($this->_db2->schema));
	}

	protected function syncDb2($output)
	{
		$this->_builder->writeTestMigration($output, '');
		$this->_migrate2->run(array('up'));
		$this->_db2->schema->refresh();
		$this->_db2->createCommand()->dropTable('tbl_migration');
		unlink($this->_builder->getMigrationPath());
	}

	protected function createBaseTable(CDbConnection $db)
	{
		$db->createCommand()->createTable('test', array(
			'id'=>'int(11) NOT NULL',
			'parent'=>'int(11) NOT NULL',
			'child'=>'int(11) NOT NULL',
			'name'=>"varchar(64) NOT NULL DEFAULT 'test'",
			'woo'=>'varchar(32) DEFAULT NULL',
			'garwr'=>'varchar(32) NOT NULL',
			'sdfds'=>'int(11) NOT NULL',
			'fkey'=>'int(11) NOT NULL',
			'PRIMARY KEY (`id`,`parent`)',
			'UNIQUE KEY `name` (`name`)',
			'KEY `woo` (`woo`)',
			'KEY `parent` (`parent`)',
			'KEY `child` (`child`)',
			'KEY `parent_2` (`parent`,`child`)',
			'KEY `sdfds` (`sdfds`)',
			'KEY `fkey` (`fkey`)',
//			'CONSTRAINT `test_ibfk_6` FOREIGN KEY (`parent`) REFERENCES `arg` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION',
//			'CONSTRAINT `test_ibfk_8` FOREIGN KEY (`child`) REFERENCES `arg` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION',
//			'CONSTRAINT `test_ibfk_9` FOREIGN KEY (`fkey`) REFERENCES `test` (`child`)',
		), 'ENGINE=InnoDB');
	}
}