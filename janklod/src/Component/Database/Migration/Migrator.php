<?php
namespace Jan\Component\Database\Migration;


use Exception;
use Jan\Component\Database\Schema\BluePrint;
use Jan\Component\Database\Schema\Schema;

/**
 * Class Migrator
 * @package Jan\Component\Database\Migration
*/
class Migrator
{
      /**
       * @var string
      */
      protected $migrationTable = 'migrations';




      /**
       * @var Schema
      */
      protected $schema;




      /**
       * @var array
      */
      protected $logs = [];




      /**
       * @var array
      */
      protected $migrations = [];




     /**
      * @var array
     */
     protected $migrationFiles = [];




     /**
      * Migrator constructor.
      * @param Schema $schema
     */
     public function __construct(Schema $schema)
     {
         $this->schema = $schema;
     }




    /**
     * Set table name for versions migrations
     *
     * @param string $migrationTable
     * @return $this
    */
    public function setMigrationTable(string $migrationTable): Migrator
    {
        $this->migrationTable = $migrationTable;

        return $this;
    }


    /**
     * @param Migration $migration
     * @return Migrator
     * @throws \ReflectionException
    */
    public function addMigration(Migration $migration): Migrator
    {
        $migrationName = $migration->getName();

        $migration->schema($this->schema);

        $this->migrations[$migrationName] = $migration;

        $this->migrationFiles[$migrationName] = $migration->getFilename();

        return $this;
    }


    /**
     * $migrations = glob("/Migrations/*.php")
     *
     * @param array $migrations
     * @return $this
     * @throws \ReflectionException
    */
    public function setMigrations(array $migrations): Migrator
    {
        foreach ($migrations as $migration) {
            if($migration instanceof Migration) {
                $this->addMigration($migration);
            }
        }

        return $this;
    }


    /**
     * @return array
     */
    public function getMigrations(): array
    {
        return $this->migrations;
    }


    /**
     * Get all applied migrations
     *
     * @return array
    */
    public function getOldMigrations(): array
    {
        return $this->schema->getConnection()
                            ->query("SELECT `migration` FROM {$this->migrationTable}")
                            ->execute()
                            ->getResult();
    }


    /**
     * @return array
    */
    public function getNewMigrations(): array
    {
        $newMigrations = [];

        foreach ($this->migrations as $migration) {
            if(! \in_array(\get_class($migration), $this->getOldMigrations())) {
                $newMigrations[] = $migration;
            }
        }

        return $newMigrations;
    }



    /**
     * Install migration version table
     *
     * @throws Exception
    */
    public function install()
    {
        $this->schema->create($this->migrationTable, function (BluePrint $table) {
            $table->increments('id');
            $table->string('migration');
            $table->datetime('executed_at');
        });
    }



    /**
     * Migrate table to the database
     *
     * @throws Exception
    */
    public function migrate()
    {
        $this->install();

        $this->applyMigrations($this->migrations);
    }



    /**
     * @throws Exception
    */
    public function diff()
    {
        $this->install();

        $this->applyMigrations($this->getNewMigrations());
    }



    /**
     * @param array $migrations
     * @throws \ReflectionException
    */
    public function applyMigrations(array $migrations)
    {
        foreach ($migrations as $migration) {
            if(method_exists($migration, 'up')) {
                $migration->up();
                $this->saveMigration($migration);
            }
        }
    }


    public function reverseMigrations()
    {
         // down(), do for one migration
    }



    /**
     * @param Migration $migration
     * @throws \ReflectionException
    */
    public function saveMigration(Migration $migration)
    {
        $result = $this->checkOneMigration($migration);

        if (! $result) {
            $sql = sprintf("INSERT INTO `%s` (migration, executed_at) VALUES ('%s', '%s')",
                $this->migrationTable,
                $migration->getName(),
                $migration->getExecutedAt()
            );

            $this->schema->getConnection()
                ->exec($sql);
        }
    }


    /**
     * @param Migration $migration
     * @return mixed
     * @throws \ReflectionException
    */
    public function checkOneMigration(Migration $migration)
    {
        $checkOne = "SELECT * FROM {$this->migrationTable} WHERE migration = :migration";

        return $this->schema->getConnection()
                               ->query($checkOne, ['migration' => $migration->getName()])
                               ->execute()
                               ->getResult();
    }




    /**
     * Drop all tables
    */
    public function rollback()
    {
        foreach ($this->migrations as $migration) {
            if(method_exists($migration, 'down')) {
                $migration->down();
            }
        }

        $this->schema->truncate($this->migrationTable);
    }



    /**
     * @throws Exception
    */
    public function reset()
    {
        $this->rollback();
        $this->schema->dropIfExists($this->migrationTable);
        $this->removeMigrationFiles();
    }



    public function resetMigrations(array $migrations)
    {

    }

    /**
     * Remove migration file
     *
     * @param Migration $migration
     * @throws \ReflectionException
    */
    public function removeMigration(Migration $migration)
    {

         $sql = sprintf("DELETE FROM %s WHERE migration = '%s'",
            $this->migrationTable,
            $migration->getName()
         );

         /*
          $sql = sprintf('DELETE FROM %s WHERE migration = :migration',
            $this->migrationTable
         );

         $this->schema->getConnection()
                      ->query($sql, ['migration' => $migration->getName()])
                      ->execute();
         */

         $this->schema->execute($sql);

         $this->removeMigrationFile($migration);
    }



    /**
     * @param Migration $migration
     * @throws \ReflectionException
    */
    public function removeMigrationFile(Migration $migration)
    {
          $migrationName = $migration->getName();

          if (isset($this->migrationFiles[$migrationName])) {
               @unlink($migration->getFilename());
          }

          unset($this->migrationFiles[$migrationName]);
    }



    /**
     * @param string $message
     * @return void
    */
    public function log(string $message)
    {
        /* echo '['. date('Y-m-d H:i:s') .'] - '. $message .PHP_EOL; */
        $this->logs[] = '['. date('Y-m-d H:i:s') .'] - '. $message .PHP_EOL;
    }



    /**
     * Remove all migration files
    */
    public function removeMigrationFiles()
    {
        array_map('unlink', $this->getMigrationFiles());
    }



    /**
     * @return array
    */
    public function getMigrationFiles(): array
    {
        return $this->migrationFiles;
    }
}


/*

$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

$configDb =  $fs->load('config/database.php');
dump($configDb);

$db = new \Jan\Component\Database\DatabaseManager();

$db->open($configDb['connection'], $configDb);

$connection = $db->connection()->getPdo();

echo '<h2>Current Connection</h2>';
dump($connection);


dump($db);
dump($db->getConnections());


echo "<h2>Database</h2>";

$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

dump($fs->scan('database/migrations'));
dump($fs->toArray('config/database.php'));

$configDb =  $fs->load('config/database.php');

$capsule = new \Jan\Component\Database\Capsule\Manager();
$type = $configDb['connection'];

$capsule->addConnection($type, $configDb);
$capsule->bootAsGlobal();

$database = \Jan\Component\Database\Capsule\Manager::instance();
dump($database);
dump($database->connection('sqlite'));
dump($database->connection());

$connection = \Jan\Component\Database\Capsule\Manager::connection();

$schema = new Jan\Component\Database\Schema\Schema($connection);

dump($schema);
$schema->create('users', function (BluePrint $table) {

});


$migrator = new \Jan\Component\Database\Migration\Migrator($schema);
dump($migrator);

$migrator->install();


$otherMigrationFiles = $fs->resources('/migrations/*.php');

dump($otherMigrationFiles);

$otherMigrations = [];

if ($otherMigrationFiles) {
    foreach ($otherMigrationFiles as $otherMigrationFile) {
        require_once $otherMigrationFile;
        $migration = pathinfo($otherMigrationFile, PATHINFO_FILENAME);
        $otherMigrations[] = new $migration();
    }
}


$defaultMigrations = [
    new \App\Migration\Version202106051623(),
    new \App\Migration\Version202106051624()
];

$migrations = array_merge($defaultMigrations, $otherMigrations);

$migrator->setMigrations($migrations);
$migrator->rollback();

$migrator->migrate();


dump($migrator->getMigrationFiles());
dump($migrator->getMigrations());
*/