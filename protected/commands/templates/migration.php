<?php
/**
 * This is the template for generating a migration sync file.
 * The following variables are available in this template:
 * - $this: the MigrationCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $builder->name; ?> extends CDbMigration
{

	public function safeUp()
	{
<?php 
//var_dump($this);
//exit;
echo $builder->beforeMigrationOutput();


echo $builder->createNewTableMigrations();


echo $builder->afterMigrationOutput();
?>
	}
	
	public function safeDown()
	{
<?php 
echo $builder->beforeMigrationOutput();
echo $builder->createDropTableMigrations();
echo $builder->afterMigrationOutput();
?>
	}
}