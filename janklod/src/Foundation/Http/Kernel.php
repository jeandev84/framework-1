<?php
namespace Jan\Foundation\Http;


use Jan\Component\Http\Request;
use Jan\Component\Http\Response;
use Jan\Contract\Http\Kernel as HttpKernelContract;



/**
 * Class Kernel
 *
 * @package Jan\Foundation\Http
*/
class Kernel implements HttpKernelContract
{

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
    */
    public function handle(Request $request): Response
    {
         return new Response();
    }



    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws Exception
    */
    public function terminate(Request $request, Response $response)
    {

    }
}