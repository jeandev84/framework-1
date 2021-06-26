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


     public function column()
     {
         return '';
     }
}