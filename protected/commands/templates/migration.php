<?php
/**
 * This is the template for generating a migration sync file.
 * The following variables are available in this template:
 * - $this: the MigrationCode object
 */
?>
<?php echo "<?php\n"; ?>

class m120922_203026_woo extends CDbMigration
{

	public function safeUp()
	{
<?php 
//var_dump($this);
//exit;
echo $builder->beforeMigrationOutput();
echo $builder->createCreateTablesMigration();
echo $builder->afterMigrationOutput();
//foreach($db->schema->tables as $table)
//	echo $table->migrationCreate($db->schema)."\n";
?>
	}
	
	public function safeDown()
	{
<?php 
echo $builder->beforeMigrationOutput();
echo $builder->createDropTablesMigration();
echo $builder->afterMigrationOutput();
//foreach($db->schema->tables as $table)
//	echo $table->migrationDrop($db->schema);
?>
	}
}