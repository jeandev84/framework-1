<?php


namespace App;


class Bar
{
     public function __construct(Foo $foo)
     {
          dump($foo);
     }
}