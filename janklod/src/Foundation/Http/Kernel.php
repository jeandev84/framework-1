<?php
namespace Jan\Foundation\Http;


use Exception;
use Jan\Component\Http\Request;
use Jan\Component\Http\Response;
use Jan\Component\Routing\Router;
use Jan\Contract\Http\Kernel as HttpKernelContract;
use Jan\Foundation\Application;


/**
 * Class Kernel
 *
 * @package Jan\Foundation\Http
*/
class Kernel implements HttpKernelContract
{


    /**
     * @var Application
    */
    protected $app;




    /**
     * @var Router
    */
    protected $router;




    /**
     * Kernel constructor.
     * @param Application $app
     * @param Router $router
    */
    public function __construct(Application $app, Router $router)
    {
         $this->app    = $app;
         $this->router = $router;
    }




    /**
     * @param Request $request
     * @return Response
     * @throws Exception
    */
    public function handle(Request $request): Response
    {
        try {
            $response = new Response('Loaded data from server ...');
        } catch (\Exception $e) {
            $response = new Response('Server error', $e->getCode());
        }

        return $response;
    }



    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws Exception
    */
    public function terminate(Request $request, Response $response)
    {
         $this->app->terminate($request, $response);
    }
}