<?php

class m120930_023154_test1 extends CDbMigration
{
	public function up()
	{
		$this->createTable('test', array(
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

	public function down()
	{
		$this->dropTable('test');
	}
}