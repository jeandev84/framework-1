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
       * @var array
      */
      protected $migrations = [];



      /**
       * @var array
      */
      protected $migrationFiles = [];




     /**
       * @var Schema
     */
     protected $schema;



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
    */
    public function addMigration(Migration $migration): Migrator
    {
        $this->migrations[] = $migration;

        return $this;
    }


    /**
     * $migrations = glob("/Migrations/*.php")
     *
     * @param array $migrations
     * @return $this
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
    public function getOldMigrations()
    {
        return $this->schema->getConnection()
                            ->query("SELECT `version` FROM {$this->migrationTable}")
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
            /*
            $table->increments('id');
            $table->string('version');
            $table->datetime('executed_at');
            */
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



    public function update()
    {
        //
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
        $this->schema->getConnection()
                     ->exec(sprintf("INSERT INTO `%s` (version, executed_at) VALUES ('%s', '%s')",
                $this->migrationTable,
                        $migration->getVersion(),
                        'GET EXECUTION TIME'
                        /* $migration->getExecutedAt() */
            )
        );
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


    /**
     * @param Migration $migration
     * @throws \ReflectionException
    */
    public function removeMigrationFile(Migration $migration)
    {
          @unlink($migration->getFilename());
    }



    /**
     * @param string $message
     * @return void
    */
    public function log(string $message)
    {
        echo '['. date('Y-m-d H:i:s') .'] - '. $message .PHP_EOL;
    }


    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
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
     * @throws \ReflectionException
    */
    public function getMigrationFiles(): array
    {
        $migrationFiles = [];

        /** @var Migration $migration */
        foreach ($this->migrations as $migration) {
            $migrationFiles[] = $migration->getFilename();
        }

        return $migrationFiles;
    }



    /**
     * @param string $className
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    protected function reflectedClass(string $className): \ReflectionClass
    {
        try {

            return new \ReflectionClass($className);

        } catch (\ReflectionException $e) {
            throw $e;
        }
    }
}