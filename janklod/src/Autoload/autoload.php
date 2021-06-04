<?php


use Jan\Autoload\Autoloader;

$autoloader = Autoloader::load(__DIR__ .'/../../');

$autoloader->map('Jan\\', 'src/')
           ->map('App\\', 'app/')
;

$autoloader->register();