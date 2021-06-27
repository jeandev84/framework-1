<?php
namespace Jan\Component\Database\Connection\PDO\Connector;


use Jan\Component\Database\Query\Exception\QueryException;
use Jan\Component\Database\Query\QueryContract;
use PDO;
use PDOStatement;



/**
 * Class Query
 *
 * @package Jan\Component\Database\Connection\PDO\Connector
*/
class Query extends QueryContract
{

    /**
     * @var PDO
    */
    protected $pdo;




    /**
     * @var PDOStatement
    */
    protected $statement;



    /**
     * @var int
    */
    protected $fetchMode = PDO::FETCH_OBJ;



    /**
     * @var string
    */
    protected $entityClass;



    /**
     * @var mixed
    */
    protected $result;



    /**
     * @var array
    */
    protected $results = [];




    /**
     * @var array
    */
    protected $bindValues = [];




    /**
     * @var array
    */
    protected $queryLogs = [];



    /**
     * Query constructor.
     *
     * @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
         $this->pdo = $pdo;
    }



    /**
     * @param int $fetchMode
     * @return $this
    */
    public function setFetchMode(int $fetchMode): Query
    {
        $this->fetchMode = $fetchMode;

        return $this;
    }



    /**
     * @return int
    */
    public function getFetchMode(): int
    {
        return $this->fetchMode;
    }


    /**
     * @param string $entityClass
     * @return $this
    */
    public function setEntityClass(string $entityClass): Query
    {
        $this->entityClass = $entityClass;

        return $this;
    }



    /**
     * @return string
    */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }


    /**
     * @param string $param
     * @param $value
     * @param int $type
     * @return $this
    */
    public function bindValue(string $param, $value, int $type = 0): Query
    {
        $this->bindValues[] = [$param, $value, $type];

        return $this;
    }



    /**
     * Execute query
    */
    public function execute(): Query
    {
        try {

            return $this->executionProcess();

        } catch (\PDOException $e) {
            throw $e;
        }
    }


    /**
     * @throws QueryException
    */
    protected function executionProcess(): Query
    {
         if (! $this->sql) {
              throw new QueryException('SQL query is empty.');
         }

         $this->statement = $this->pdo->prepare($this->sql);

         if ($this->bindValues) {
              return $this->executeBindValues($this->statement);
         }

         return $this->executeParams($this->statement);
    }


    /**
     * @param PDOStatement $statement
     * @return Query
    */
    public function executeBindValues(PDOStatement $statement): Query
    {
         $params = [];

         foreach ($this->bindValues as $bindParameters) {
              list($param, $value, $type) = $bindParameters;
              $statement->bindValue(':'. $param, $value, $type);
              $params[$param] = $value;
         }

         if ($this->statement->execute()) {
             $this->log($this->sql, $params);
         }

         return $this->setResults($statement);
    }



    /**
     * @param PDOStatement $statement
     * @return Query
    */
    public function executeParams(PDOStatement $statement): Query
    {
        if($statement->execute($this->params)) {
            $this->log($this->sql, $this->params);
        }

        return $this->setResults($statement);
    }



    /**
     * @param PDOStatement $statement
     * @return $this
    */
    public function setResults(PDOStatement $statement): Query
    {
        $this->results = $this->fetchResults($statement);
        $this->result  = $this->fetchResult($statement);

        return $this;
    }


    /**
     * @param PDOStatement $statement
     * @return array
    */
    public function fetchResults(PDOStatement $statement): array
    {
        if($this->entityClass) {
            return $statement->fetchAll(PDO::FETCH_CLASS, $this->entityClass);
        }

        return $statement->fetchAll($this->fetchMode);
    }


    /**
     * @param PDOStatement $statement
     * @return mixed
    */
    public function fetchResult(PDOStatement $statement)
    {
        if($this->entityClass) {
            $statement->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);
            return $statement->fetch();
        }

        return $statement->fetch($this->fetchMode);
    }



    /**
     * @return array
    */
    public function getResult(): array
    {
        return $this->results;
    }



    /**
     * @return mixed
    */
    public function getOneOrNullResult()
    {
         return $this->result;
    }



    /**
     * @param string $sql
     * @param array $params
     * @return void
    */
    public function log(string $sql, array $params = [])
    {
         $this->queryLogs[$sql] = $params;
    }
}