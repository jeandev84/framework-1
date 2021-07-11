<?php
namespace Jan\Express;



/**
 * Class App
 * @package Jan\Express
*/
class App
{


    /**
     * @var Container
    */
    protected $container;




    /**
     * App constructor.
     * @param array $config
    */
    public function __construct(array $config)
    {
        $this->container = new Container($config);
    }

    /**
     * @param string $path
     * @param \Closure $closure
     * @return void
     */
    public function get(string $path, \Closure $closure) {

    }



    public function run()
    {

    }
}