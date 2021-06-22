<?php

return [
/*
|------------------------------------------------------------------
|     CONNECTION TO DATABASE [ mysql, sqlite, pgsql, oci (oracle) ]
|------------------------------------------------------------------
*/

    'connection' => 'mysql', // mysql, sqlite, pgsql, oci
    'sqlite' => [
        'driver'   => 'sqlite',
        'database' => '../demo.sqlite',
        'options'  => []
    ],
    'mysql' => [
        'driver'    => 'mysql',
        'database'  => 'mvc_framework',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'username'  => 'root',
        'password'  => '',
        'collation' => 'utf8_unicode_ci',
        'charset'   => 'utf8',
        'prefix'    => '',
        'engine'    => 'InnoDB', // MyISAM
        'options'   => [],
    ]
];