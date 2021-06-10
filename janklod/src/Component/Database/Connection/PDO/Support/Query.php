<?php
namespace Jan\Component\Database\Connection\PDO\Support;


use Exception;
use Jan\Component\Database\Connection\Exception\QueryException;
use Jan\Component\Database\Contract\QueryInterface;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Class Query
 * @package Jan\Component\Database\Connection\PDO\Support
*/
class Query
{


    /**
     * @var string
    */
    protected $sql;


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
     * @var array
    */
    protected $params  = [];




    /**
     * Statement constructor.
     * @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * @param $fetchMode
     * @return $this
     */
    public function setFetchMode($fetchMode): Query
    {
        $this->fetchMode = $fetchMode;

        return $this;
    }




    /**
     * @param $entityClass
     * @return $this
    */
    public function setEntityClass($entityClass): Query
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
     * @return Query
    */
    public function execute(): Query
    {
        try {

            $this->statement = $this->pdo->prepare($this->sql);

            if($this->bindValues) {
                return $this->executeBindValuesProcess($this->statement);
            }

            return $this->executeParamsProcess($this->statement);

        } catch (PDOException $e) {

            throw $e;
        }
    }



    /**
     * @param string $sql
     * @param array $params
    */
    public function log(string $sql, array $params = [])
    {
        if($sql !== '') {
            $this->queryLogs[$sql] = $params;
        }
    }



    /**
     * @return array
     */
    public function getQueryLog(): array
    {
        return $this->queryLogs;
    }


    /**
     * @return array
     * @throws Exception
     */
    public function getResult(): array
    {
        return $this->results;
    }


    /**
     * @return mixed
     * @throws Exception
    */
    public function getFirstResult()
    {
        return $this->result[0] ?? null;
    }




    /**
     * @return mixed
    */
    public function getSingleResult()
    {
        return $this->result;
    }




    /**
     * @param PDOStatement $statement
     *
     * @return $this
    */
    protected function executeBindValuesProcess(PDOStatement $statement): Query
    {
        $this->params = [];
        $params = [];

        foreach ($this->bindValues as $bindParameters) {
            list($param, $value, $type) = $bindParameters;
            $statement->bindValue(":". $param, $value, $type);
            $params[$param] = $value;
        }

        if($statement->execute()) {
            $this->log($this->sql, $params);
        }

        $this->setResults($statement);

        return $this;
    }



    /**
     * @param PDOStatement $statement
     *
     * @return $this
    */
    protected function executeParamsProcess(PDOStatement $statement): Query
    {
        $this->bindValues = [];
        if($statement->execute($this->params)) {
            $this->log($this->sql, $this->params);
        }

        $this->setResults($statement);

        return $this;
    }



    /**
     * @param PDOStatement $statement
     * @return Query
    */
    protected function setResults(PDOStatement $statement): Query
    {
        $this->results = $this->fetchAll($statement);
        $this->result  = $this->fetchOne($statement);

        return $this;
    }



    /**
     * @param PDOStatement $statement
     * @return array
    */
    private function fetchAll(PDOStatement $statement): array
    {
        if ($this->entityClass) {
            return $statement->fetchAll(PDO::FETCH_CLASS, $this->entityClass);
        }

        return $statement->fetchAll($this->fetchMode);
    }



    /**
     * @param PDOStatement $statement
     * @return mixed
    */
    private function fetchOne(PDOStatement $statement)
    {
        if($this->entityClass) {
            $statement->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);
            return $statement->fetch();
        }

        return $statement->fetch($this->fetchMode);
    }
}