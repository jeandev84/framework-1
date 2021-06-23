<?php
namespace Jan\Component\Database\Connection\PDO\Statement;


use PDO;


/**
 * Class Statement
 * @package Jan\Component\Database\Connection\PDO\Statement
*/
class Query
{

     /**
      * @var PDO
     */
     protected $pdo;



     /**
      * Statement constructor.
      * @param PDO $pdo
     */
     public function __construct(PDO $pdo)
     {
          $this->pdo = $pdo;
     }
}