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
echo $builder->beforeMigrationOutput();
echo $up;
echo $builder->afterMigrationOutput();
?>
	}

	public function safeDown()
	{
<?php
echo $builder->beforeMigrationOutput();
echo $down;
echo $builder->afterMigrationOutput();
?>
	}
}