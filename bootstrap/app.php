<?php
/*
|-------------------------------------------------------------------
|    Create new application
|-------------------------------------------------------------------
*/

$app = new \Jan\Foundation\Application(
    realpath(__DIR__.'/../')
);


/*
|-------------------------------------------------------------------
|    Binds important interfaces of application
|-------------------------------------------------------------------
*/
$app->singleton(
Jan\Contract\Http\Kernel::class,
App\Http\Kernel::class
);

$app->singleton(
Jan\Contract\Console\Kernel::class,
App\Console\Kernel::class
);

$app->singleton(
Jan\Contract\Debug\ExceptionHandler::class,
App\Exception\ErrorHandler::class
);



/*
|-------------------------------------------------------------------
|    Return instance of application
|-------------------------------------------------------------------
*/
return $app;