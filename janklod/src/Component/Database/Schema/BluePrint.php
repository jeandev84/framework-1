<?php
namespace Jan\Component\Database\Schema;


/**
 * Class BluePrint
 * @package Jan\Component\Database\Schema
*/
class BluePrint
{

     /**
      * @var string
     */
     protected $table;



     /**
      * BluePrint constructor.
      * @param string $table
     */
     public function __construct(string $table)
     {
         $this->table = $table;
     }



     /**
      * @param string $column
      * @return void
     */
     public function increments(string $column)
     {
         echo $column;
     }


     /**
      * @param string $column
      * @return void
     */
     public function string(string $column)
     {
         echo $column;
     }


     /**
      * @param string $column
     */
     public function datetime(string $column)
     {
         echo  (new \DateTime())->format('Y-m-d H:i:s');
     }
}